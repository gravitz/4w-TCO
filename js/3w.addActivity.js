$(document).ready(function () {
	$( "#region" ).change(function() {
		//Populate district dropdown options based on entered regions.
		getDistricts ($('#region').val()); 
	});
	
	$( "#activityClassification" ).change(function() {
		//Populate activivity subclassification dropdown options based on input classification.
		getSubClassifications ($("#activityClassification option:selected").val());
	});

	//Error message area.
	$('#message-container').html("<?php echo $message; ?>");
	//$('#myModal').find('p').html('<span style="color:#4F2817;">'+alertMsg+'</span>');
	//modal.find('.modal-body p').text('You are about to remove this entry. Are you sure?');
	
	//alert ('<?php echo $message; ?>');
	
	/*var modal = $('#message-container');
	modal.find('.modals-body p').text("<?php echo $message; ?>");
	//modal.find('.modal-header h4').html('Remove entry');
	//modal.find('.modal-footer a.btn').text('Remove');

	$('#message-container').modal('show');
	*/
	
	/*
		Next few lines implement organisation autocomplete, using twitter typeahead and bloodhound suggestion engines
	*/
	
	//initialise a bloodhound instance, that fetches suggestions from a webservice
	var organisations = new Bloodhound({
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		datumTokenizer: function (datum) {
			return Bloodhound.tokenizers.whitespace(datum.value);
		},
		/*prefetch: {
			url: '../includes/orgs.json',
			cache: false
		},*/
		remote: {
			url: '../includes/getorganisations.php?org=%QUERY',
			cache: false,
			wildcard: '%QUERY',
			transform: function (response) {
				return $.map(response, function (org) {
					return org;
				});
			}
		}
	});
	
	//initialise  a typeahead instance that sources suggestions from the bloodhound instance created above
	$('.text_org').typeahead({
		hint: false,
		highlight: true,
		minLength: 1, //starts a lookup on receiving the first letter of input. To limit network calls, this may need to be increased with a bigger number of organisations in the database
		autoselect: false
	},
	{
		name: 'organisations',
		source: organisations, //bloodhound instance
		display: function(item) { 
		    //what to display in dropdown options
			return item.value;
		},
		limit: 5 //show a maximum of 5 suggestions
	});
	
	//Variable to keep track of, and distinguish between dropdown selections in typeahead, and free text
	var isTypeaheadSelect = false;
	
	//The following is the handler once a typeahead selection is made from suggestions
	$('.text_org').bind('typeahead:select', function(ev, item) {
		isTypeaheadSelect = true;
		//$('.text_org').typeahead("getActive").on('typeahead:change').unbind();

		//Make orgtype selection for selected organisation and disable the dropdown 
		$('[name="Orgs[OrgType][]"]').first().selectpicker('val', item.type);
		$('[name="Orgs[OrgType][]"]').first().prop('disabled', true);
		$('[name="Orgs[OrgType][]"]').first().selectpicker('refresh');
		
		//Clear orgrole selection for selected organisation 
		$('[name="Orgs[OrgRole][]"]').first().selectpicker('val', '');
		$('[name="Orgs[OrgRole][]"]').first().selectpicker('refresh');
		
		//Move focus to org role
		$("[name='Orgs[OrgRole][]']").focus();
	});
	
	//The following is the handler for when a selection change (input loses focus and the value has changed since it originally received focus.) has occurred. Written to handle cases when no selection is made from suggestions, but free text is input
	$('.text_org').bind('typeahead:change', function(ev, item) {
		if (isTypeaheadSelect) {
			//Do nothing
		}
		else {
			//Make orgtype selection for selected organisation and enable the dropdown 
			$('[name="Orgs[OrgType][]"]').first().selectpicker('val', '');
			$('[name="Orgs[OrgType][]"]').first().prop('disabled', false);
			$('[name="Orgs[OrgType][]"]').first().selectpicker('refresh');
			
			//Clear orgrole selection for selected organisation 
			$('[name="Orgs[OrgRole][]"]').first().selectpicker('val', '');
			$('[name="Orgs[OrgRole][]"]').first().selectpicker('refresh');
		}
		isTypeaheadSelect = false;
	});
	
	//Next lines handle cloning to allow entering multiple organisations, and their roles
	$('.clonedInput').on('added',function(evt){
		//new typeahead instance for the newly created Organisation input element
		$(evt.target).typeahead({
			hint: false,
			highlight: true,
			minLength: 1,
			autoselect: false
		},
		{
			name: 'organisations',
			source: organisations,
			display: function(item){
				return item.value;
			},
			limit: 5
		});
		
		//Selection handler for the clone
		$(evt.target).bind('typeahead:select', function(ev, item) {
			isTypeaheadSelect = true;
			
			//Next line gets the latest clone index in the series, to be used to prefil values for Org type for each Organisation clone
			var curIdx =  evt.target.name.match(/\d/g).join("");
			
			//Make orgtype selection for selected organisation and disable the dropdown 
			$('[name="Orgs[OrgType][' + curIdx + ']').selectpicker('val', item.type);
			$('[name="Orgs[OrgType][' + curIdx + ']').prop('disabled', true);
			$('[name="Orgs[OrgType][' + curIdx + ']').selectpicker('refresh');
			
			//Clear orgrole selection for selected organisation 
			$('[name="Orgs[OrgRole][' + curIdx + ']').selectpicker('val', '');
			$('[name="Orgs[OrgRole][' + curIdx + ']').selectpicker('refresh');
			
			//Move focus to org role
			$('[name="Orgs[OrgRole][' + curIdx + ']').focus();
		});	
		
		//change handler for the clone
		$(evt.target).bind('typeahead:change', function(ev, item) {
	
			//Next line gets the current clone index in the series, to be used to prefil values for Org type for each Organisation clone
			var curIdx =  ev.target.name.match(/\d/g).join("");
			
			if (isTypeaheadSelect) {
				//Do nothing
			}
			else {
				
				//Make orgtype selection for selected organisation and disable the dropdown 
				$('[name="Orgs[OrgType][' + curIdx + ']').selectpicker('val', '');
				$('[name="Orgs[OrgType][' + curIdx + ']').prop('disabled', false);
				$('[name="Orgs[OrgType][' + curIdx + ']').selectpicker('refresh');
				
				//Clear orgrole selection for selected organisation 
				$('[name="Orgs[OrgRole][' + curIdx + ']').selectpicker('val', '');
				$('[name="Orgs[OrgRole][' + curIdx + ']').selectpicker('refresh');
			}
			isTypeaheadSelect = false;
		});
	});	
	
	/*
	//Next section handles changes in organisation input field, for when a typeahead selection has been made
	$('[name*="Orgs[Organization]"]').change(function() {
		//console.log($(this));// + " Org changed");
		var current = $('[name*="Orgs[Organization]"]').typeahead("getActive");
		if (current) {
			// Some item from your model is active!
			if (current.name == $('#Organization').val()) {
				// This means the exact match is found. Use toLowerCase() if you want case insensitive match.
				console.log(current[0]);
			} else {
				// This means it is only a partial match, you can either add a new item 
				// or take the active if you don't want new items
			}
		} else {
			// Nothing is active so it is a new value (or maybe empty value)
		}
	});
	*/
	
				
	/*var isAfterStartDate = function(startDateStr, endDateStr) {
            var inDate = new Date(inDateStr),
                eDate = new Date(endDateStr);

            if(inDate < eDate) {
                return false;
            }

        };
	jQuery.validator.addMethod("isAfterStartDate", function(value, element) {
		return isAfterStartDate($('#sDate').val(), value);
    }, "End date should be after start date");
	*/
	
	/* 
		Next section handles cloning of organisation and related form elements. 
		Based on code available: @ https://github.com/tristandenyer/Clone-section-of-form-using-jQuery
	*/
	$('#btnAdd').click(function () {
		var num = $('.clonedInput').length, // Checks for how many clone input fields we currently have
        newNum  = new Number(num + 1),  // The numeric ID of the new input field being added in sequence 
			
		//Next line creates a clone for #cloneableSec minus the buttons (only orgi. copy suffices)
        newElem = $('#cloneableSec' + num).clone(true, false).find('.btn').remove().end().attr('id', 'cloneableSec' + newNum).fadeIn('slow'); // create the new element via clone(), and manipulate it's ID using newNum value
			
		/*  
			This is where we manipulate the name/id values of the input inside the new, cloned element
		*/
        
		// Heading section {H5} (Organisation number in sequence)
        newElem.find('.heading-reference').attr('id', 'ID' + newNum + '_reference').attr('name', 'ID' + newNum + '_reference').html('Organisation #' + newNum);

        // Organisation name section {text}
		newElem.find('.lbl_org').attr('for', 'Orgs[Organization][' + (newNum-1) + ']'); //label
		newElem.find('.text_org').attr('name', 'Orgs[Organization][' + (newNum-1) + ']').val(''); //input field
		
        // Org Type section {selectpicker - dropdown }
		newElem.find('.lbl_orgtype').attr('for', 'Orgs[OrgType][' + (newNum-1) + ']'); //label
		newElem.find('.input_orgtype').attr('name', 'Orgs[OrgType][' + (newNum-1) + ']').val(''); //input field - clear value
		//newElem.find('.input_orgtype option:not(:selected)').prop('disabled', false); //input field - enable
		newElem.find('.input_orgtype').prop('disabled', false); //input field - enable
		//newElem.find('.input_orgtype').selectpicker('refresh')

        // Org Role section {selectpicker - dropdown }
        newElem.find('.lbl_orgrole').attr('for', 'Orgs[OrgRole][' + (newNum-1) + ']'); //label
		newElem.find('.input_orgrole').attr('name', 'Orgs[OrgRole][' + (newNum-1) + ']').val(''); //input field clear value

		// Insert the new element after the last "duplicatable" input field
        $('#cloneableSec' + num).after(newElem);
		
		//Move focus to orgnanization field of newly cloned section (delayed to await creation of element)
		setTimeout(function() {
			$("[name='Orgs[Organization][" + (newNum-1) + "]']").focus();
		}, 100);
		
		//Trigger a custom event to inform that element has been created
		newElem.find('.text_org').trigger('added');
		
		/*
			Next lines: Workaround from https://github.com/silviomoreto/bootstrap-select/issues/605
			to allow correct cloning of bootstrap selectpickers 
		*/
		
		newElem.find('.input_orgtype').replaceWith(function() { return $('select', this); });
		newElem.find('.input_orgrole').replaceWith(function() { return $('select', this); });
        newElem.find('select').selectpicker();	
		//workaround ends here

		// Enable the "remove" button. This only shows once you have a cloned section.
        $('#btnDel').attr('disabled', false);

		// Limit number of clones to 4 sections, for a total of 5.
        if (newNum == 5)
			$('#btnAdd').attr('disabled', true); // disable the clone add button
    });
	
	//Handle clone deletion
    $('#btnDel').click(function () {
		// Confirmation dialog box. 
        if (confirm("Are you sure you wish to remove this Organisation? This cannot be undone.")) {
			var num = $('.clonedInput').length;
			// how many cloned input fields we currently have
			$('#cloneableSec' + num).slideUp('slow', function () {
				$(this).remove();
							   
				// if only one element remains, disable the "remove" button
				if (num-1 === 1) {
					$('#btnDel').attr('disabled', true);
				}
				
				// enable the "add" button
				$('#btnAdd').attr('disabled', false);
			});
		}
        return false; // Removes the last section you added
    });
	
    // Enable the "add" button
    $('#btnAdd').attr('disabled', false);
    
	// Disable the "remove" button
    $('#btnDel').attr('disabled', true);
	
	/*
		End of cloning code
	*/
	
	$('#add-activity-form').submit(function() {
		// Enable all OrgType selects before just before submission to give them POST values
		$('.input_orgtype').prop('disabled', false);
		return true; 
	});
	
	// Validation rules using jquery.validate library
	$('#add-activity-form').validate({
		rules: {
			activity: {
				required: true
			},
			desc: {
				required: false
			},
			sDate: {
				required: false,
				date: true
			},
			eDate: {
				required: false,
				date: true
				//isAfterStartDate: true
			},
			activityClassification: {
				required: true
			},
			activitySubClassification: {
				required: false
			},
			activityTheme: {
				required: false
			},
			iOrganization: {
				required: true
			},
			iOrgType: {
				required: true
			},
			fOrganization: {
				required: true
			},
			fOrgType: {
				required: true
			},
			activityStatus: {
				required: false
			},
			activityType: {
				required: false
			},
			fundingStatus: {
				required: false
			},
			fundingType: {
				required: false
			},
			fundingCurrency: {
				required: false
			},
			fundingAmount: {
				required: false,
				digits: true
			},
			focalPoint: {
				required: false
			},
			region: {
				required: true
			},
			district: {
				required: false
			},
			ward: {
				required: false
			},
		}, //end of validation rules
		highlight: function (element) {
			$(element).closest('.control-group').removeClass('success').addClass('error');
		},
		success: function (element) {
			element./*text('OK!').*/addClass('valid')
				.closest('.control-group').removeClass('error').addClass('success');
		}
	});
});//end of ready

/**
 * Function to fetch districts from a webservice, and populate response (dropdown options) into
 * #district select input
 * @param region
 * @return boolean
 */
function getDistricts (region) {
	$.ajax({
	  url:'../includes/getdistricts.php',
	  type: 'post',
	  data: {'reg': region},
	  complete: function (response) {
		  //console.log(response.responseText);
		 $("#district").html(response.responseText).selectpicker('refresh');
	  },
	  error: function () {
		 console.log('Bummer! An error occurred getting districts.');
	  }
  });
  return false;
}

/**
 * Function to fetch activity subsclassification from a webservice, and populate response (dropdown options) into
 * #activitySubClassification select input
 * @param classification
 * @return boolean
 */
function getSubClassifications (classification) {
	$.ajax({
	  url:'../includes/getsubs.php',
	  type: 'post',
	  data: {'class': classification},
	  complete: function (response) {
		 //console.log(response.responseText);
		 $("#activitySubClassification").html(response.responseText).selectpicker('refresh');
	  },
	  error: function () {
		 console.log('Bummer! An error occurred getting districts.');
	  }
  });
  return false;
}
