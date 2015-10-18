function changeColour(field) {
	
	if(field=='systolic'){
		if(document.getElementById("blood_sys").value < 130) {  
		document.getElementById("blood_sys").style.background = '#00FF33'; // Green
		}
		
		if(document.getElementById("blood_sys").value > 129 && document.getElementById("blood_sys").value < 140) {
		document.getElementById("blood_sys").style.background = '#FF0'; // Yellow
		}
		
		if(document.getElementById("blood_sys").value > 139 && document.getElementById("blood_sys").value < 180) {
		document.getElementById("blood_sys").style.background = '#F90'; // Orange
		}
		
		if(document.getElementById("blood_sys").value > 179) {
		document.getElementById("blood_sys").style.background = '#F00'; // Red
		}
	}
	
	if(field=='diastolic'){
		if(document.getElementById("blood_dia").value < 85) {  
		document.getElementById("blood_dia").style.background = '#00FF33'; // Green
		}
		
		if(document.getElementById("blood_dia").value > 84 && document.getElementById("blood_dia").value < 90) {
		document.getElementById("blood_dia").style.background = '#FF0'; // Yellow
		}
		
		if(document.getElementById("blood_dia").value > 89 && document.getElementById("blood_dia").value < 110) {
		document.getElementById("blood_dia").style.background = '#F90'; // Orange
		}
		
		if(document.getElementById("blood_dia").value > 109) {
		document.getElementById("blood_dia").style.background = '#F00'; // Red
		}
	}
	
	
	if(field=='oxygenation'){
		if(document.getElementById("oxigen_blood_hemoglobin").value > 95) {  
		document.getElementById("oxigen_blood_hemoglobin").style.background = '#00FF33'; // Green
		}
		
		if(document.getElementById("oxigen_blood_hemoglobin").value > 92 && document.getElementById("oxigen_blood_hemoglobin").value < 96) {
		document.getElementById("oxigen_blood_hemoglobin").style.background = '#FF0'; // Yellow
		}
		
		if(document.getElementById("oxigen_blood_hemoglobin").value > 89 && document.getElementById("oxigen_blood_hemoglobin").value < 93) {
		document.getElementById("oxigen_blood_hemoglobin").style.background = '#F90'; // Orange
		}
		
		if(document.getElementById("oxigen_blood_hemoglobin").value < 90) {
		document.getElementById("oxigen_blood_hemoglobin").style.background = '#F00'; // Reg
		}
	}
	
	if(field=='temperature'){
		if(document.getElementById("temperature").value < 37) {  
		document.getElementById("temperature").style.background = '#00FF33'; // Green
		}
		
		if(document.getElementById("temperature").value > 36.9 && document.getElementById("temperature").value < 37.5) {
		document.getElementById("temperature").style.background = '#FF0'; // Yellow
		}
			
		if(document.getElementById("temperature").value > 37.4) {
		document.getElementById("temperature").style.background = '#F90'; // Orange
		}
		
		var calcius=document.getElementById("temperature").value;
		var far=(calcius*1.8)+32;
		document.getElementById("temp_in_f").innerHTML=far.toFixed(2)+" &deg;F";
	}
	
	if(field=='blood_sugar'){
		
		<!-- FBS --->
		if(document.getElementById("blood_glucose_status").value =='FBS'){	
			if(document.getElementById("blood_glucose_unit").value =='mg/dL')
			{
				if(document.getElementById("blood_sugar").value < 100) {  
				document.getElementById("blood_sugar").style.background = '#00FF33'; // Green
				}
				
				if(document.getElementById("blood_sugar").value > 99 && document.getElementById("blood_sugar").value < 126) {
				document.getElementById("blood_sugar").style.background = '#FF0'; // Yellow
				}
				
				if(document.getElementById("blood_sugar").value > 125 && document.getElementById("blood_sugar").value < 200) {
				document.getElementById("blood_sugar").style.background = '#F90'; // Orange
				}
				
				if(document.getElementById("blood_sugar").value > 199) {  
				document.getElementById("blood_sugar").style.background = '#F00'; // red
				}
			}
			
			if(document.getElementById("blood_glucose_unit").value =='mmol/L')
			{
				if(document.getElementById("blood_sugar").value < 5.5) {  
				document.getElementById("blood_sugar").style.background = '#00FF33'; // Green
				}
				
				if(document.getElementById("blood_sugar").value > 5.5 && document.getElementById("blood_sugar").value < 7) {
				document.getElementById("blood_sugar").style.background = '#FF0'; // Yellow
				}
				
				if(document.getElementById("blood_sugar").value > 6.94 && document.getElementById("blood_sugar").value < 11.11) {
				document.getElementById("blood_sugar").style.background = '#F90'; // Orange
				}
				
				if(document.getElementById("blood_sugar").value > 11.05) {  
				document.getElementById("blood_sugar").style.background = '#F00'; // red
				}
			}
			
		}
		
		<!-- PBS --->
		if(document.getElementById("blood_glucose_status").value =='PBS'){		
			
			if(document.getElementById("blood_glucose_unit").value =='mg/dL')
			{
				if(document.getElementById("blood_sugar").value < 140) {  
				document.getElementById("blood_sugar").style.background = '#00FF33'; // Green
				}
				
				if(document.getElementById("blood_sugar").value > 139 && document.getElementById("blood_sugar").value < 200) {
				document.getElementById("blood_sugar").style.background = '#FF0'; // Yellow
				}
				
				if(document.getElementById("blood_sugar").value > 199 && document.getElementById("blood_sugar").value < 300) {
				document.getElementById("blood_sugar").style.background = '#F90'; // Orange
				}
				
				if(document.getElementById("blood_sugar").value > 299) {  
				document.getElementById("blood_sugar").style.background = '#F00'; // red
				}
			}
			
			if(document.getElementById("blood_glucose_unit").value =='mmol/L')
			{
				if(document.getElementById("blood_sugar").value < 7.77) {  
				document.getElementById("blood_sugar").style.background = '#00FF33'; // Green
				}
				
				if(document.getElementById("blood_sugar").value > 7.72 && document.getElementById("blood_sugar").value < 11.11) {
				document.getElementById("blood_sugar").style.background = '#FF0'; // Yellow
				}
				
				if(document.getElementById("blood_sugar").value > 11.05 && document.getElementById("blood_sugar").value < 16.66) {
				document.getElementById("blood_sugar").style.background = '#F90'; // Orange
				}
				
				if(document.getElementById("blood_sugar").value > 16.61) {  
				document.getElementById("blood_sugar").style.background = '#F00'; // red
				}
			}
			
			
		}
						
		
	}
	
	
	if(field=='blood_hemoglobin'){
			
			if(document.getElementById("blood_hemoglobin").value > 11) {  
			document.getElementById("blood_hemoglobin").style.background = '#00FF33'; // Green
			}
			
			if(document.getElementById("blood_hemoglobin").value > 9 && document.getElementById("blood_hemoglobin").value < 12) {
			document.getElementById("blood_hemoglobin").style.background = '#FF0'; // Yellow
			}
			
			if(document.getElementById("blood_hemoglobin").value > 7 && document.getElementById("blood_hemoglobin").value < 10) {
			document.getElementById("blood_hemoglobin").style.background = '#F90'; // Orange
			}
			
			if(document.getElementById("blood_hemoglobin").value < 8) {  
			document.getElementById("blood_hemoglobin").style.background = '#F00'; // red
			}
	}
	
	if(field=='unine_sugar'){
			if(document.getElementById("unine_sugar").value == '-') 
			{  
			document.getElementById("unine_sugar").style.backgroundColor='#00FF33';	 // Green			
			}
			else if(document.getElementById("unine_sugar").value == '+-')
			{
			document.getElementById("unine_sugar").style.backgroundColor='#FF0';	 // Yellow				
			}
			else if(document.getElementById("unine_sugar").value == '')
			{
			document.getElementById("unine_sugar").style.backgroundColor='#FFF';	 // white				
			}
			else
			{
			document.getElementById("unine_sugar").style.backgroundColor='#F90';	 // Orange			
			}
								
	}
	
	if(field=='urine_protein'){
			if(document.getElementById("urine_protein").value == '-') 
			{  
			document.getElementById("urine_protein").style.backgroundColor='#00FF33';	 // Green			
			}
			else if(document.getElementById("urine_protein").value == '+-')
			{
			document.getElementById("urine_protein").style.backgroundColor='#FF0';	 // Yellow				
			}
			else if(document.getElementById("urine_protein").value == '')
			{
			document.getElementById("urine_protein").style.backgroundColor='#FFF';	 // white				
			}
			else
			{
			document.getElementById("urine_protein").style.backgroundColor='#F90';	 // Orange			
			}
								
	}
	
	if(field=='urinary_urobilinogen'){
			if(document.getElementById("urinary_urobilinogen").value == '+-')
			{  
			document.getElementById("urinary_urobilinogen").style.backgroundColor='#00FF33';	 // Green			
			}
			else
			{
			document.getElementById("urinary_urobilinogen").style.backgroundColor='#F90';	 // Orange			
			}
								
	}
	
	if(field=='urinary_ph'){
			
			if(document.getElementById("urinary_ph").value > 7 && document.getElementById("urinary_ph").value <= 9) {
		document.getElementById("urinary_ph").style.backgroundColor='#00FF33';	 // Green	
			}			
			else if(document.getElementById("urinary_ph").value > 6 && document.getElementById("urinary_ph").value <= 7) 
			{
			document.getElementById("urinary_ph").style.backgroundColor='#FF0';	 // Yellow				
			}			
			else if(document.getElementById("urinary_ph").value >= 5 && document.getElementById("urinary_ph").value <= 6)
			{
			document.getElementById("urinary_ph").style.backgroundColor='#F90';	 // orange
			}
			else if(document.getElementById("urinary_ph").value < 5)
			{
			document.getElementById("urinary_ph").style.backgroundColor='#F00';	 // red
			}
			else
			{
			document.getElementById("urinary_ph").style.backgroundColor='#FFF';	 // white			
			}
								
	}
	
	if(field=='pulse_ratio'){

			if(document.getElementById("pulse_ratio").value > 59 && document.getElementById("pulse_ratio").value < 100){  
			document.getElementById("pulse_ratio").style.background='#00FF33';	 // Green			
			}
			
			if(document.getElementById("pulse_ratio").value > 49 && document.getElementById("pulse_ratio").value < 60){  
			document.getElementById("pulse_ratio").style.background='#FF0';	 // Yellow			
			}
			
			if(document.getElementById("pulse_ratio").value > 99 && document.getElementById("pulse_ratio").value < 120){  
			document.getElementById("pulse_ratio").style.background='#FF0';	 // Yellow			
			}
			
			if(document.getElementById("pulse_ratio").value < 50 || document.getElementById("pulse_ratio").value > 119){  
			document.getElementById("pulse_ratio").style.background='#F90';	 // Orange			
			}			
								
	}
	
	
	if(field=='rhythm'){
			if(document.getElementById("rhythm").value == 'Normal')
			{  
			document.getElementById("rhythm").style.backgroundColor='#00FF33';	 // Green			
			}
			else if(document.getElementById("rhythm").value == 'Abnormal')
			{
			document.getElementById("rhythm").style.backgroundColor='#F90';	 // Orange			
			}
			else
			{
			document.getElementById("hbsag").style.backgroundColor='#FFF';	 // white
			}								
	}
	
	if(field=='cholesterol'){
			if(document.getElementById("cholesterol").value <= 200)
			{  
			document.getElementById("cholesterol").style.backgroundColor='#00FF33';	 // Green			
			}
			else if(document.getElementById("cholesterol").value > 200 && document.getElementById("cholesterol").value <= 225)
			{
			document.getElementById("cholesterol").style.backgroundColor='#FF0';	 // Yellow
			}
			else if(document.getElementById("cholesterol").value > 225 && document.getElementById("cholesterol").value <= 239)
			{
			document.getElementById("cholesterol").style.backgroundColor='#F90';	 // orange
			}
			else if(document.getElementById("cholesterol").value > 239)
			{
			document.getElementById("cholesterol").style.backgroundColor='#F00';	 // red
			}								
	}
	
	if(field=='uric_acid'){
		
		<!-- uric_acid Male --->
		if(document.getElementById("sex").value =='Male'){
			if((document.getElementById("uric_acid").value > 3.5) && (document.getElementById("uric_acid").value <= 7)) {
			document.getElementById("uric_acid").style.background = '#00FF33'; // Green
			}
			else if((document.getElementById("uric_acid").value > 7) && (document.getElementById("uric_acid").value < 8)) {
			document.getElementById("uric_acid").style.background = '#F90';	 // orange
			}
			else if(document.getElementById("uric_acid").value >= 8) {
			document.getElementById("uric_acid").style.background = '#F00';	 // red
			}
			else
			{
			document.getElementById("uric_acid").style.background = '#00FF33'; // Green
			}
		}
		
		<!-- uric_acid Female --->
		if(document.getElementById("sex").value =='Female'){
			if((document.getElementById("uric_acid").value > 2.4) && (document.getElementById("uric_acid").value <= 6)) {
			document.getElementById("uric_acid").style.background = '#00FF33'; // Green
			}
			else if((document.getElementById("uric_acid").value > 6) && (document.getElementById("uric_acid").value < 7)) {
			document.getElementById("uric_acid").style.background = '#F90';	 // orange
			}
			else if(document.getElementById("uric_acid").value >= 7) {
			document.getElementById("uric_acid").style.background = '#F00';	 // red
			}
			else
			{
			document.getElementById("uric_acid").style.background = '#00FF33'; // Green
			}
		}
		
	}
	
	if(field=='hbsag'){
			if(document.getElementById("hbsag").value == 'Negative')
			{  
			document.getElementById("hbsag").style.backgroundColor='#00FF33';	 // Green			
			}
			else if(document.getElementById("hbsag").value == 'Positive')
			{
			document.getElementById("hbsag").style.backgroundColor='#F00';	 // red
			}
			else
			{
			document.getElementById("hbsag").style.backgroundColor='#FFF';	 // white
			}
								
	}
	
	<!-- alert ("This box must be filled!"); -->
	<!--document.getElementById("Q49I1029").focus(); -->
	<!-- return false;  -->
}//end Function

function getAge() {
var birthDate= document.getElementById("date_of_birth").value
  var today = new Date();
   var curr_date = today.getDate();
   var curr_month = today.getMonth() + 1;
   var curr_year = today.getFullYear();

   var pieces = birthDate.split('-');
   var birth_date = pieces[0];
   var birth_month = pieces[1];
   var birth_year = pieces[2];

   if (curr_month == birth_month && curr_date >= birth_date) age= parseInt(curr_year-birth_year);
   if (curr_month == birth_month && curr_date < birth_date) age= parseInt(curr_year-birth_year-1);
   if (curr_month > birth_month) age= parseInt(curr_year-birth_year);
   if (curr_month < birth_month) age= parseInt(curr_year-birth_year-1);
  document.getElementById("age").value = age;
}


function bmi_calculation()
{
var height=document.getElementById("height").value;	
var weight=document.getElementById("weight").value;
var height_meeter=height/100;
var bmi= weight/(height_meeter*height_meeter);
var round_bmi= bmi.toFixed(2)
document.getElementById("bmi").value=round_bmi;	

<!-- color  -->
	if(document.getElementById("bmi").value < 25) {  
	document.getElementById("bmi").style.background = '#00FF33'; // Green
	}

	if(document.getElementById("bmi").value > 24.9 && document.getElementById("bmi").value < 30) {
		document.getElementById("bmi").style.background = '#FF0'; // Yellow
	}
	
	if(document.getElementById("bmi").value > 29.9 && document.getElementById("bmi").value < 35) {
		document.getElementById("bmi").style.background = '#F90'; // Orange
	}
	
	if(document.getElementById("bmi").value > 35) {  
	document.getElementById("bmi").style.background = '#F00'; // Green
	}

}

function waist_hip_ratio_calculation()
{
var waist_circumferenc=document.getElementById("waist_circumference").value;
var hip=document.getElementById("hip").value;
var waist_hip_ratio=waist_circumferenc/hip;
var round_waist_hip_ratio= waist_hip_ratio.toFixed(2)
document.getElementById("waist_hip_ratio").value=round_waist_hip_ratio;	

<!-- Waist Male --->
if(document.getElementById("sex").value =='Male'){
	if(document.getElementById("waist_circumference").value <90){
	document.getElementById("waist_circumference").style.background = '#00FF33'; // Green
	}
	
	if(document.getElementById("waist_circumference").value >89){
	document.getElementById("waist_circumference").style.background = '#FF0'; // Yellow
	}
}

<!-- Waist Female --->
if(document.getElementById("sex").value =='Female'){
	if(document.getElementById("waist_circumference").value <80){
	document.getElementById("waist_circumference").style.background = '#00FF33'; // Green
	}
	
	if(document.getElementById("waist_circumference").value >79){
	document.getElementById("waist_circumference").style.background = '#FF0'; // Yellow
	}
}

<!--Waist Hip Ration--->
if(document.getElementById("sex").value =='Male'){
	if(document.getElementById("waist_hip_ratio").value <0.90){
	document.getElementById("waist_hip_ratio").style.background = '#00FF33'; // Green
	}
	
	if(document.getElementById("waist_hip_ratio").value >0.89){
	document.getElementById("waist_hip_ratio").style.background = '#FF0'; // Yellow
	}
}

if(document.getElementById("sex").value =='Female'){
	if(document.getElementById("waist_hip_ratio").value <0.85){
	document.getElementById("waist_hip_ratio").style.background = '#00FF33'; // Green
	}
	
	if(document.getElementById("waist_hip_ratio").value >0.84){
	document.getElementById("waist_hip_ratio").style.background = '#FF0'; // Yellow
	}
}


}

//function nameempty()
//{
//if ( document.formID.reg_no.value == '' )
//	{
//	alert('No Registration no. was entered!')
//	return false;
//	}
//
//if ( document.formID.name.value == '' )
//	{
//	alert('Please enter a patient name')
//	return false;
//	}
//
//}

function runScript(e) {
    if (e.keyCode == 13) {
        var tb = document.getElementById("reg_no");		 
        document.getElementById("name").focus();
		eval(tb.value);
        return false;
    }
}  