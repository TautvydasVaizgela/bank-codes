<?php

/* 

Programėlę sudaro trys pagrindinės dalys:

1. Duomenų nuskaitymas į masyvą.

2. Ciklų pagalba nagrinėjant kiekvieną masyvo elementą (trijų skaitmenų kodukai), "tvarkyti" slaptažodį, 
tikrinant kiekvieną koduko elementą ir kiekvieną slaptažodžio elementą žiūrėt kurie sutampa ir kurie ne. 
Iš esmės vyksta slaptažodžio skaitmenų keitimas vietomis, kad būtu patenkinama pagrindinė uždavinio sąlyga.

3. Nesutampantys skaitmenys iš kodukų nagrinėjami trečioje dalyje, "switch" pagalba nustatoma kur reikėtų
"lipdyti" naujus koduko skaičius egzistuojančiame slaptažodyje.

*/

// Pirma dalis - "duomenų nuskaitymas"

$codes = file("codes.txt", FILE_IGNORE_NEW_LINES);

$codeslength = count($codes);
$password = ""; // Šiame kintamajame bus sudaromas pilnas slaptažodis

// Antra dalis - "Slaptažodžio tvarkymas"

for($i = 0; $i < $codeslength; $i++) { //Ciklas sukamas kiekvienam masyvo elementui (kodukui)
    
// Reikalingi kintamieji sąlygų tikrinimui

$pirm_poz = -1;
$pirm_sk = -1;

$antr_poz = -1;
$antr_sk = -1;

$trec_poz = -1;
$trec_sk = -1;


	if($i == 0){
        
        $password = $codes[$i]; //Sudaromas "pradinis" slaptažodis pagal pirmą masyvo elementą
    }
    
    else{
        
        $number = $codes[$i];
        
        for($j = 0; $j < 3; $j++) //Ciklas sukamas kiekvienam koduko elementui
        {
            for($k = 0; $k < strlen($password); $k++) //Ciklas sukamas kiekvienam slaptažodžio elementui
            {   
                if($number[$j] == $password[$k]) //Tikrinama ar rasta koduko elementas jau egzistuojančiame slaptažodyje, jei taip, vykdomas tvarkymas.
                {
                    if($j == 0) //Jeigu randamas pirmas koduko elementas
                    {
                        $pirm_poz = $k;
                        $pirm_sk = $number[$j];
                    }
                    if($j == 1) //Jeigu randamas antras koduko elementas
                    {
                        $antr_poz = $k;
                        $antr_sk = $number[$j];
                        
                        if($antr_poz < $pirm_poz)
                        {
                            $tmp = $password[$antr_poz];
                            $password[$antr_poz] = $password[$pirm_poz];
                            $password[$pirm_poz] = $tmp;
							
							$tmp = $antr_poz;
							$antr_poz = $pirm_poz;
							$pirm_poz = $tmp;
                        }
                    }
                    if($j == 2) ////Jeigu randamas trečias koduko elementas
                    {
                        $trec_poz = $k;
                        $trec_sk = $number[$j];
                        
                        if($trec_poz < $pirm_poz)
                        {
                            $tmp = $password[$trec_poz];
                            $password[$trec_poz] = $password[$pirm_poz];
                            $password[$pirm_poz] = $tmp;
							
							$tmp = $trec_poz;
							$trec_poz = $pirm_poz;
							$pirm_poz = $tmp;
                        }
						
						if($trec_poz < $antr_poz)
                        {
                            $tmp = $password[$trec_poz];
                            $password[$trec_poz] = $password[$antr_poz];
                            $password[$antr_poz] = $tmp;
							
							$tmp = $trec_poz;
							$trec_poz = $antr_poz;
							$antr_poz = $tmp;
                        }
                    }
                }
            }
        }
		
// Trečia dalis - "Naujų skaitmenų iš koduko "prilipdymas" į slaptažodžio reikiamas vietas
// Viso yra 8 galimi atvėjai, kuriais pasibaigia pirmoji uždavinio dalis, kiekvienas atvėjis
// tikrinamas "switch" pagalba.
		
		switch(true)
		{
			case ($pirm_sk == -1 && $antr_sk != -1 && $trec_sk != -1): // Koduko elementas slaptažodyje nerasta, rasta, rasta
			
				$password = substr_replace($password, $number[0], $antr_poz, 0);
				break;
			
			case ($pirm_sk != -1 && $antr_sk == -1 && $trec_sk != -1): // Koduko elementas slaptažodyje rasta, nerasta, rasta
			
				$password = substr_replace($password, $number[1], $pirm_poz+1, 0);
				break;
			
			case ($pirm_sk != -1 && $antr_sk != -1 && $trec_sk == -1): // Koduko elementas slaptažodyje rasta, rasta, nerasta
			
				$password = substr_replace($password, $number[2], $antr_poz+1, 0);
				break;
			
			case ($pirm_sk != -1 && $antr_sk == -1 && $trec_sk == -1): // Koduko elementas slaptažodyje rasta, nerasta, nerasta
			
				$password = substr_replace($password, $number[1], $pirm_poz+1, 0);
				$password = substr_replace($password, $number[2], $pirm_poz+2, 0);
				break;
			
			case ($pirm_sk == -1 && $antr_sk != -1 && $trec_sk == -1): // Koduko elementas slaptažodyje nerasta, rasta, nerasta
			
				$password = substr_replace($password, $number[0], $antr_poz, 0);
				$password = substr_replace($password, $number[2], $antr_poz+2, 0);
				break;
			
			case ($pirm_sk == -1 && $antr_sk == -1 && $trec_sk != -1): // Koduko elementas slaptažodyje nerasta, nerasta, rasta
			
				$password = substr_replace($password, $number[1], $trec_poz, 0);
				$password = substr_replace($password, $number[0], $trec_poz-2, 0);
				break;
			
			case ($pirm_sk == -1 && $antr_sk == -1 && $trec_sk == -1): // Koduko elementas slaptažodyje nerasta, nerasta, nerasta
			
				$password .= $number; // Tiesiog pridedamas kodukas gale slaptažodžio, kadangi kol kas daugiau duomenų apie jį nėra.

				break;
			
			default:
			
				break; //Aštuntas atvėjis - visi trys koduko elementai rasti slaptažodyje, todėl šios "lipdymo" dalies nereikia
		}
    }
	
	//echo $password;
	//echo "<br />";
}

echo $password;

?>