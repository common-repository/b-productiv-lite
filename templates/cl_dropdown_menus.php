<?php
	/*-------------------------------------------------------------------------------
	** File Name: 
	**			cl_dropdown_menu.php
	**
	** File Description:  
	** 			Serves as the file used to store the drop down menus for the
	**			B-Productiv Plugin
	**
	** File Last Updated On: 
	**			6/12/2018
	**
	** Original Author: 
	**			Clyde A. Lettsome, PhD, PE
	**
	** Last Editor: 
	**			Clyde A. Lettsome, PhD, PE
	**
	** File Layer: 
	** 			Service Layer
	**
	** File Calls//Submits: 
	**			All Portal Pages 
	**			 
	** Accessible By:
	**			Everyone
	**
	** Notes: 
	** 			Copyright (c) C. A. Lettsome Services, LLC. 2018  All rights reserved.
	**			http://www.clydelettsome.com
	*-------------------------------------------------------------------------------*/
	$months = Array("January","February","March","April","May","June","July","August","September","October","November","December");
	$days = Array("Su", "M", "T", "W", "Th", "F", "Sa");
	$month_n = Array("","01","02","03","04","05","06","07","08","09","10","11","12");
	$hour_n = Array("","12am", "1am","2am","3am","4am","5am", "6am", "7am","8am","9am", "10am", "11am", "12pm", "1pm", "2pm", "3pm","4pm","5pm", "6pm", "7pm","8pm","9pm", "10pm", "11pm");
	$hour_v = Array("","00", "01","02","03","04","05", "06", "07","08","09", "10", "11", "12", "13", "14", "15","16","17", "18", "19","20","21", "22", "23");
	if($_SESSION['country']=="US"||$country=="US")
	{
		$state_n = Array("","Alabama(AL)","Alaska(AK)","Arizona(AZ)","Arkansas(AR)", "American Samoa(AS)","Colorado(CO)","Connecticut(CT)", "California(CA)", "Delaware(DE)", "District of Columbia(DC)", "Florida(FL)","Georgia(GA)","Guam(GU)","Hawaii(HI)","Idaho(ID)","Illinois(IL)","Indiana(IN)","Iowa(IA)","Kansas(KS)","Kentucky(KY)","Louisiana(LA)","Maine(ME)","Maryland(MD)","Massachusetts(MA)","Michigan(MI)","Minnesota(MN)","Mississippi(MS)","Missouri(MO)","Montana(MT)","Nebraska(NE)","Nevada(NV)","New Hampshire(NH)","New Jersey(NJ)","New Mexico(NM)","New York(NY)","North Carolina(NC)","North Dakota(ND)", "Northern Mariana Islands", "Ohio(OH)","Oklahoma(OK)","Oregon(OR)","Pennsylvania(PA)","Puerto Rico(PR)","Rhode Island(RI)","South Carolina(SC)","South Dakota(SD)","Tennessee(TN)","Texas(TX)","Utah(UT)","Vermont(VT)","Virgin Islands(VI)","Virginia(VA)","Washington(WA)","West Virginia(WV)","Wisconsin(WI)","Wyoming(WY)"); 
		$state_v = Array("","AL","AK","AZ","AR", "AS","CO","CT", "CA", "DE", "DC", "FL","GA","GU","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","MP","OH","OK","OR","PA","PR","RI","SC","SD","TN","TX","UT","VT","VI","VA","WA","WV","WI","WY");		  
	}
	elseif ($_SESSION['country']=="CA"||$country=="CA")
	{
		$state_n = Array("","Alberta(AB)", "British Columbia(BC)", "Manitoba(MB)", "New Brunswick(NB)", "Newfoundland and Labrador(NL)", "Northwest Territories(NT)", "Nova Scotia(NS)", "Nunavut(NU)", "Ontario(ON)", "Prince Edward Island(PE)", "Quebec(QC)", "Saskatchewan(SK)", "Yukon(YT)");
		$state_v = Array("", "AB", "BC", "MB", "NB", "NL", "NT", "NS", "NU", "ON", "PE", "QC", "SK", "YT");
	}
	elseif($_SESSION['country']=="AU"||$country=="AU")
	{
		$state_n = Array("", "Australian Capital Territory(ACT)", "New South Wales(NSW)", "Northern Territory(NT)", "Queensland(QLD)", "South Australia(SA)", "Tasmania(TAS)", "Victoria(VIC)", "Western Australia(WA)");
		$state_v = Array("", "ACT", "NSW", "NT", "QLD", "SA", "TAS", "VIC", "WA");
	}
	else
	{
		$state_n = Array("Other");
		$state_v = Array("Other");
	}
	$country_v= Array("",
	"AI",
	"AG",
	"AW",
	"AU",
	"BS",
	"BB",
	"BZ",
	"BM",
	"BW",
	"IO",
	"BN",
	"KH",
	"CM",
	"CA",
	"CV",
	"KY",
	"CK",
	"DM",
	"DO",
	"ER",
	"ET",
	"FK",
	"FJ",
	"GM",
	"GH",
	"GI",
	"GD",
	"GP",
	"GY",
	"HN",
	"HK",
	"IN",
	"IE",
	"IM",
	"IL",
	"JM",
	"JE",
	"KE",
	"KI",
	"LB",
	"LS",
	"LR",
	"MG",
	"MW",
	"MY",
	"MT",
	"MH",
	"MU",
	"FM",
	"MS",
	"NA",
	"NR",
	"AN",
	"NZ",
	"NG",
	"NU",
	"NF",
	"PK",
	"PW",
	"PH",
	"PN",
	"RW",
	"BL",
	"SH",
	"KN",
	"LC",
	"MF",
	"PM",
	"VC",
	"WS",
	"SC",
	"SL",
	"SG",
	"SB",
	"SO",
	"ZA",
	"SD",
	"SZ",
	"TZ",
	"TK",
	"TO",
	"TT",
	"TC",
	"TV",
	"UG",
	"GB",
	"US",
	"VU",
	"VG",
	"ZM",
	"ZW");
	$country_n= Array("",
	 "Anguilla",
	 "Antigua and Barbuda",
	 "Aruba",
	 "Australia",
	 "Bahamas",
	 "Barbados",
	 "Belize",
	 "Bermuda",
	 "Botswana",
	 "British Indian Ocean Territory",
	 "Brunei",
	 "Cambodia",
	 "Cameroon",
	 "Canada",
	 "Cape Verde",
	 "Cayman Islands",
	 "Cook Islands",
	 "Dominica",
	 "Dominican Republic",
	 "Eritrea",
	 "Ethiopia",
	 "Falkland Islands",
	 "Fiji",
	 "Gambia",
	 "Ghana",
	 "Gibraltar",
	 "Grenada",
	 "Guadeloupe",
	 "Guyana",
	 "Honduras",
	 "Hong Kong",
	 "India",
	 "Ireland",
	 "Isle of Man",
	 "Israel",
	 "Jamaica",
	 "Jersey",
	 "Kenya",
	 "Kiribati",
	 "Lebanon",
	 "Lesotho",
	 "Liberia",
	 "Madagascar",
	 "Malawi",
	 "Malaysia",
	 "Malta",
	 "Marshall Islands",
	 "Mauritius",
	 "Micronesia",
	 "Montserrat",
	 "Namibia",
	 "Nauru",
	 "Netherlands Antilles",
	 "New Zealand",
	 "Nigeria",
	 "Niue",
	 "Norfolk Island",
	 "Pakistan",
	 "Palau",
	 "Philippines",
	 "Pitcairn",
	 "Rwanda",
	 "Saint Barthélemy",
	 "Saint Helena",
	 "Saint Kitts and Nevis",
	 "Saint Lucia",
	 "Saint Martin (French part)",
	 "Saint Pierre and Miquelon",
	 "Saint Vincent and the Grenadines",
	 "Samoa",
	 "Seychelles",
	 "Sierra Leone",
	 "Singapore",
	 "Solomon Islands",
	 "Somalia",
	 "South Africa",
	 "Sudan",
	 "Swaziland",
	 "Tanzania",
	 "Tokelau",
	 "Tonga",
	 "Trinidad and Tobago",
	 "Turks and Caicos Islands",
	 "Tuvalu",
	 "Uganda",
	 "United Kingdom",
	 "United States of America",
	 "Vanuatu",
	 "Virgin Islands ( British )",
	 "Zambia",
	 "Zimbabwe");
	 
	$TimeZone_n = Array("American Samoa","Honolulu","Aleutian Islands","Anchorage","Los Angeles","Phoenix","Denver","Chicago","New York City","Caracas","San Juan","St.John's","Sao Paulo","Cape Verde","London","Lagos","Cairo","Moscow","Tehran","Baku","Kabul","Karachi","Delhi","Kathmandu","Dhaka","Yangon","Jakarta","Hong Kong","Tokyo","Adelaide","Sydney","Nouméa","Auckland","Samoa");

	$TimeZone_v = Array("US/Samoa","US/Hawaii","US/Aleutian","US/Alaska","US/Pacific",	"US/Arizona","US/Mountain","US/Central","US/Eastern","America/Caracas","America/Puerto_Rico","America/St_Johns","America/Sao_Paulo","Atlantic/Cape_Verde","Europe/London","Africa/Lagos","Africa/Cairo","Europe/Moscow","Asia/Tehran","Asia/Baku","Asia/Kabul","Asia/Karachi","Asia/Kolkata","Asia/Kathmandu","Asia/Dhaka","Asia/Yangon","Asia/Jakarta","Asia/Hong_Kong","Asia/Tokyo","Australia/Adelaide","Australia/Sydney","Pacific/Noumea","Pacific/Auckland","Pacific/Apia");
?>