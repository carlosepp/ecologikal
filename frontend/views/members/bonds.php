<?php
require_once($_SERVER['DOCUMENT_ROOT']."/_config/bootstrap.php");
$_SESSION['user_hash'] = members_get_info('hash',$_SESSION['user_id']);
// If a parameter is passed with the user_id then it displays the profile for the user_id passed, if not it means that should display loggedin user profile
if (isset($_GET['user_id']) || isset($_GET['user'])){
	if(isset($_GET['user'])){
		$userid = members_get_user_id($_GET['user']);
	}else{
		$userid = $_GET['user_id'];
	}


	if ($userid == $_SESSION['user_id']){
		$myprofile = true;
	}else{
		$myprofile = false; // flag to not display editable features
	}
}else{
	$myprofile = true; // flag to allow editable features
	$userid = $_SESSION['user_id'];
}
// Check for edit mode ?edit=1 to edit
if(isset($_GET['edit'])){
	$edit = $_GET['edit'];
}else{
	$edit =0;
}
?>
<div class="padding10 whitefont">
  <div id="bonds">

    <?php $members = members_get_friends($userid); ?>
    <h3 class="whitefont"><div class="ecoicon">&amp;</div>Bonds <span class="friendcount">(<?php echo count($members);?>)</span><span class="viewall"><a href="#">View all</a></span></h3>

  <?php	if ($members == null && $myprofile){
      echo "You have no Friends <a href='"._ROOT_URL_."membersdir.php'>Start Making Bonds</a>";
    }else{?>
    <friends>
        <?php foreach ($members as $key => $value) { ?>
          <a href="<?php members_display_profile_url($members[$key]['user_id']) ?>" ><memberavatar class="tiptip" title="<?php echo $members[$key]['name'].' '.$members[$key]['lastname']; ?>"><?php members_display_profile_thumb($members[$key]['user_id']); ?></memberavatar></a>
        <?php }	// end For?>
    </friends>
    <?php } //End if?>

  </div>
</div>
<? if ($edit == 1) :?>
<script>
$(document).ready(function(e){
	saveurl = BACKEND_URL + 'members/savememberdata.php';
	// Add the edit icon
	$('fields.editmode div').hover(function(e){
		$(this).append('<div class="editicon"><span class="ui-icon ui-icon-pencil"></span></div>');
	}, function(e){
		$(this).find('div.editicon').remove();
	});

	$('fields.editmode div').click(function(e){
		$(this).find('span.editable').trigger('click');
	});
	// Add Custom Editable Types

	$.editable.addInputType('dob', {
		element: function(settings, original){
			var dayselect = $('<select id="day">');
			var monthselect = $('<select id="month">');
			var yearselect = $('<select id="year">');

			// Days loop
			for(var days=1; days<32; days++){
				var option = $('<option />').val(days).append(days);
				dayselect.append(option);
			}
			$(this).append(dayselect);

			//Month Loop
			var month=new Array(12);
			month[0]="January";
			month[1]="February";
			month[2]="March";
			month[3]="April";
			month[4]="May";
			month[5]="June";
			month[6]="July";
			month[7]="August";
			month[8]="September";
			month[9]="October";
			month[10]="November";
			month[11]="December";
			for(var months=0; months<12; months++){
				var option = $('<option />').val(month[months]).append(month[months]);
				monthselect.append(option);
			}
			$(this).append(monthselect);

			//Year Loop
			for(var years=2011; years>1900; years--){
				var option = $('<option />').val(years).append(years);
				yearselect.append(option);
			}
			$(this).append(yearselect);
			var hidden = $('<input type="hidden">');
							$(this).append(hidden);
							return(hidden);
		},
		submit: function(settings,original){
			var value = $('#day').val() + " - " + $('#month').val() + " - " + $('#year').val();
			$('input', this).val(value);
		}

	});
	$.editable.addInputType('phone', {
		element: function(settings, original){
			var phone = $('<input type="text" id="phone">').mask('(99) 999 99 999 99');
			$(this).append(phone);
			var hidden = $('<input type="hidden">');
							$(this).append(hidden);
							return(hidden);
		},
		submit: function(settings,original){
			var value = $('#phone').val();
			$('input', this).val(value);
		}

	});
	$.editable.addInputType('autocomplete', {
					element : $.editable.types.text.element,
					plugin : function(settings, original) {
							$('input', this).autocomplete(settings.autocomplete.data);
					}
	});


	// Adding JEditable to specific fields

	$('.email').editable( saveurl,{
		name	: 'email',
		submit: 'Save',
		placeholder   : 'Click to edit Email',

	});

	$('.country').editable( saveurl,{
		name	: 'country',
		type	: 'autocomplete',
		onblur    : "submit",
		placeholder   : 'Country',
		submit: 'Save',
		autocomplete : {
		data : ["Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia and Herzegovina",
				"Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","Brunei Darussalam","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island",
				"Cocos (Keeling) Islands","Colombia","Comoros","Congo","Congo, the Democratic Republic of the","Cook Islands","Costa Rica","Cote D'Ivoire","Croatia","Cuba","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt",
				"El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands (Malvinas)","Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","French Southern Territories","Gabon","Gambia","Georgia","Germany","Ghana",
				"Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guinea","Guinea-Bissau","Guyana","Haiti","Heard Island and Mcdonald Islands","Holy See (Vatican City State)","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia",
				"Iran, Islamic Republic of","Iraq","Ireland","Israel","Italy","Jamaica","Japan","Jordan","Kazakhstan","Kenya","Kiribati","Korea, Democratic People's Republic of","Korea, Republic of","Kuwait","Kyrgyzstan","Lao People's Democratic Republic","Latvia",
				"Lebanon","Lesotho","Liberia","Libyan Arab Jamahiriya","Liechtenstein","Lithuania","Luxembourg","Macao","Macedonia, the Former Yugoslav Republic of","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania",
				"Mauritius","Mayotte","Mexico","Micronesia, Federated States of","Moldova, Republic of","Monaco","Mongolia","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zeland",
				"Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Palestinian Territory, Occupied","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico",
				"Qatar","Reunion","Romania","Russian Federation","Rwanda","Saint Helena","Saint Kitts and Nevis","Saint Lucia","Saint Pierre and Miquelon","Saint Vincent and the Grenadines","Samoa","San Marino",	"Sao Tome and Principe","Saudi Arabia","Senegal",
				"Serbia and Montenegro","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia and the South Sandwich Islands","Spain","Sri Lanka","Sudan","Suriname","Svalbard and Jan Mayen","Swaziland",
				"Sweden","Switzerland","Syrian Arab Republic","Taiwan, Province of China","Tajikistan",	"Tanzania, United Republic of","Thailand","Timor-Leste","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Turks and Caicos Islands",
				"Tuvalu","Uganda",	"Ukraine","United Arab Emirates","United Kingdom","United States","United States Minor Outlying Islands","Uruguay","Uzbekistan","Vanuatu","Venezuela","Viet Nam","Virgin Islands, British",	"Virgin Islands, U.S.",	"Wallis and Futuna",
				"Western Sahara","Yemen","Zambia","Zimbabwe"]
						}
	});
	$('.genderselect').editable(saveurl,{
			name	: 'gender',
			data   : "{'Male':'Male','Female':'Female','Not Disclosed':'Not Disclosed'}",
				type   : "select",
				style  : "inherit",
			placeholder   : 'Gender',
			submit : "Save",
				submitdata : function() {
					return {id : 2};
				}
	});
	$('.dobselect').editable(saveurl,{
		name: 'dob',
		type: 'dob',
		style: 'display:inline',
		submit: 'Save',
		placeholder   : 'Date of Birth',
	});
	$('.phone').editable(saveurl,{
		name: 'phone',
		type: 'phone',
		submit: 'Save',
		placeholder   : 'Phone',
	});
	$('.firstname').editable(saveurl,{
		name: 'firstname',
		submit: 'Save',
		placeholder   : 'First Name',
	});
	$('.lastname').editable(saveurl,{
		name: 'lastname',
		submit: 'Save',
		placeholder   : 'Last Name'
	});
	$('.aboutme').click(function(e){
		$(this).find('div.editicon').remove();
	})
	$('.aboutme').editable(saveurl,{
		name: 'aboutme',
		type: 'textarea',
		submit: 'Save',
		placeholder   : 'Write something about you...'
	});
	// Manage Languages

	queryurl = BACKEND_URL + '_general/getlanguagelist.php';

	$('#language').autoSuggest(queryurl,{
		selectedItemProp: 'value',
		selectedValuesProp: 'id',
		startText: "Type Language",
		searchObjProps: "value",
		minChars: 2,
		preFill: selectedData.items,
		matchCase: false,
		//Adds country code to the list
		formatList: function(data,elem){
			var level = data.level, language = data.value;
			var new_elem = elem.html( language+" - "  +level);
			return new_elem;
		}
	}).watermark('Type Language').focus();
	$('button.add').live('click',function(e){
		langs = $('input.as-values').val();
		$.post(BACKEND_URL+'members/savememberdata.php', { language: langs},function(data){
				//$('#languages').append(data);
				window.location.href = "?";
		});
	});
});
</script>
<? endif; ?>