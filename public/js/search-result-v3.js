(function( $ ) {
	'use strict';
	$(document).ready(function(){
		// PM or FM contact details
		let row = $("#mra-search-result").data('saved');
		let search_template = $("#mra-search-result").templateify('mra-search-result', {}, function(section){
			section.find("form.search_provider").on('submit', function(e){
		 		e.preventDefault();
		 		let resultSection = $(this).find('.search_results');
		 		search_template.setData({providers:row, loader:true,error:false});
		 		let data = $(this).serializeArray();
		 		let formData = {};
		 		for(var d in data){
		 			formData[data[d].name] = data[d].value;
		 		}
		 		$.ajax({
					url: admin.base_url+"/?rest_route=/mra/v1/lookup",
					type: 'post',
					data: formData,
					success: function(response) {
						if(response.status){
							// let address = {
							// 	street: response.result.street || "",
							// 	houseno: response.result.HouseNr || "",
							// 	zipcode: response.result.Zipcode || "",
							// 	city: response.result.City || "",
							// 	Houseext:response.result.HouseNrExt || ""
							// }
							let address = response.result.street ? response.result.street+" " : "";
							address += response.result.HouseNr ? response.result.HouseNr+" " : "";
							address += response.result.HouseNrExt ? response.result.HouseNrExt+" " : "";
							address += response.result.Zipcode ? response.result.Zipcode+" " : "";
							address += response.result.City ? response.result.City+" " : "";
							response.result.available.map(function(cv){
								cv.MaxDownload = bytesToSize(cv.MaxDownload);
								cv.MaxUpload = bytesToSize(cv.MaxUpload);
                                if(cv.pricing) {
                                    cv.pricing.map(function(p){
                                        p.MaxDownload = bytesToSize(p.MaxDownload);
                                        p.MaxUpload = bytesToSize(p.MaxUpload);
                                        p.option = p.MaxDownload+" download / "+p.MaxUpload+" upload";
                                        return p;
                                    });
                                }
								return cv;
							});
                            let manpricing = JSON.parse(pricings.manual);
							let addition = 0;
							for(let key in manpricing){
								if (pricings.price_calculation == "percentage") {
									addition = (parseInt(manpricing[key])*parseInt(pricings.pricing_margin))/100;
								} else if (pricings.price_calculation == "fixed"){
									addition = parseInt(pricings.pricing_margin);
								}
								manpricing[key] = parseInt(manpricing[key]) + addition;
							  };
							search_template.setData({providers:response.result.available,error:false, loader:false, addr:address, manualpricings: manpricing});
                            section.find("select.provider-pricings").trigger("change");
						} else {
                            if (response.msg == "Your API is not verified yet, Please verify your API first."){
                                search_template.setData({loader:false, providers:[],error:true, errorMsg: response.msg});
                            } else {
                                search_template.setData({loader:false, providers:[],error:true});
                            }
						}
					}
				});

		 	});
		 	section.find(".zipcheck-offer-btn").on("click", function(e){
		 		let company = $(this).data("company");
		 		let provider = $(this).data("provider");
		 		let type = $(this).data("ptype");
		 		let download = $(this).data("download");
		 		let upload = $(this).data("upload");
		 		let area = $(this).data("area");
		 		let distance = $(this).data("distance");
		 		let address = $(this).data("address");
		 		let carrier = $(this).data("carrier");
		 		$("input#form_company").val(company);
		 		$("input#form_ptype").val(type);
		 		$("input#form_provider").val(provider);
		 		$("input#form_download").val(download);
		 		$("input#form_upload").val(upload);
		 		$("input#form_area").val(area);
		 		$("input#form_distance").val(distance);
		 		$("input#form_address").val(address);
		 		$("input#form_carrier").val(carrier);
                $("input#form_type").val("offer");
				$("#request_quote_submit").text("Offerte Aanvragen");
		 		var modal = document.getElementById("qupraModal");
		 		modal.style.display = "block";
		 	});
            section.find(".zipcheck-order-btn").on("click", function(e){
                let company = $(this).data("company");
                let provider = $(this).data("provider");
                let type = $(this).data("ptype");
                let download = $(this).data("download");
                let upload = $(this).data("upload");
                let area = $(this).data("area");
                let distance = $(this).data("distance");
                let address = $(this).data("address");
                let carrier = $(this).data("carrier");
                $("input#form_company").val(company);
                $("input#form_ptype").val(type);
                $("input#form_provider").val(provider);
                $("input#form_download").val(download);
                $("input#form_upload").val(upload);
                $("input#form_area").val(area);
                $("input#form_distance").val(distance);
                $("input#form_address").val(address);
                $("input#form_carrier").val(carrier);
               $("input#form_type").val("order");
			   $("#request_quote_submit").text("Bestellen");
                var modal = document.getElementById("qupraModal");
                modal.style.display = "block";
            });
		 	section.find(".qupra_close").on("click", function(e){
		 		var modal = document.getElementById("qupraModal");
		 		modal.style.display = "none";
		 	});
		 	section.find("form#requestForm").on("submit", function(e){
		 		e.preventDefault();
		 		let data = $(this).serializeArray();
		 		let formData = {};
		 		for(var d in data){
		 			if(!(data[d].name == "area" || data[d].name == "distance" || data[d].name == "address" || data[d].name == "carrier")){
			 			if(data[d].value == ""){
			 				alert(data[d].name+ " is required");
			 				return;
			 			}
		 			}
		 			formData[data[d].name] = data[d].value;
		 		}
		 		$("#request_quote_submit").text("Sending Request...");

		 		$.ajax({
					url: admin.base_url+"/?rest_route=/mra/v1/sendMail",
					type: 'post',
					data: formData,
					success: function(response) {
						if(response.status){
							$("#request_quote_submit").text("Request Sent...");
							setTimeout(function(){
								var modal = document.getElementById("qupraModal");
								modal.style.display = "none";
							},1000);
							$("#request_quote_submit").text("Offerte Aanvragen");
						}
					}
				});
		 	});
            section.find("select.provider-pricings").on("change", function(e){
                let index = $(this).data("index");
                let value = $(this).val();
                $(".manual-price-"+index).css("display", "none");
                console.log(value);
                console.log(index);
                console.log(".manual-price[data-price='"+value+"']");
                $(".manual-price[data-price='"+value+"']").css("display","inline-block");
            });
		});
		search_template.setData({providers:row});
	});
    // $("select.provider-pricings").on("change", function(e){
    //     let index = $(this).data("index");
    //     let value = $(this).val();
    //     $(".manual-price").css("display", "none");
    //     console.log(value);
    //     console.log(index);
    //     console.log(".manual-price[data-price='"+value+"']");
    //     $(".manual-price[data-price='"+value+"']").css("display","inline-block");
    // });
	function save_data(data){
		console.log(data);
		$("#mra-search-result").attr('data-saved', data);
	}
	function bytesToSize(bytes) {
	   var sizes = ['Kbps', 'Mbps', 'Gbps', 'Tbps'];
	   if (bytes == 0) return '0 Byte';
	   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
	}
})( jQuery );
