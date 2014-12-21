		<!-- Match IPs to Subnet range.-->
		<?php
			$arrCSV = array();
			$arrCSVCity = array();
			// Open the CSV
			if (($read_handle = fopen("ipsubnetoutput.csv", "r")) !==FALSE) 
			{
				// Set the parent array key to 0
				$key = 0;
				$index=0;
				// While there is data available loop through unlimited times (0) using separator (,)
				while (($data = fgetcsv($read_handle, 0, ",")) !==FALSE) 
				{
					//Count the total keys in each row
					$c = count($data);
					//Populate the array
					if($key!=0)
					for($x=1;$x<3;$x++)
					{
						$arrCSV[$index++] = $data[1];
						$arrCSVCity[$index++] = $data[14];
					}				   
					$key++;
				} // end while
				// Close the CSV file
				fclose($read_handle);
			} // end if

			$l=array_values(array_unique($arrCSV));
			$ci=array_values(array_unique($arrCSVCity));
			//echo print_r($l).'<br/>';
			$locations=array();
			$cities=array();
			for($x=0;$x<count($l);$x++)
				$locations[$l[$x]]=0;
			for($x=0;$x<count($ci);$x++)
				$cities[$ci[$x]]=0;
			
		?>
		<?php

			//Read first line from file and ignore it bcoz it is for header of column.
			$file_handle_ip_input = fopen("alert_ip.csv", "r");
			$line_of_text_ip_input = fgetcsv($file_handle_ip_input, 2048);

			$file_handle_ip_output = fopen("found_ip_details_alert_unique_ip.csv", "w");
			$fields=array('IP','Division','Location','Tower','Floor','Wing','Subnet','SubnetCat','SubnetMask','No of hosts','Host range','Latitude','Longitude');
			fputcsv($file_handle_ip_output,$fields);

			$file_handle_ip_not_found_output = fopen("not_found_ip_details_alert_unique_ip.csv", "w");
			fputcsv($file_handle_ip_not_found_output, $line_of_text_ip_input);

			$fc=0;//Found IPs counter.
			$nfc=0;//Not found IPs counter.
			$count=0;//IPs counter.
			//Read first/next IP.
			$counter=1;

			$line_of_text_ip_input = fgetcsv($file_handle_ip_input, 2048);
			print "\n";

			while (!feof($file_handle_ip_input) ) 
			{
				//Convert IP from text to float.
				print count($line_of_text_ip_input)."\t$counter\n";
				$search_ip=(float)sprintf("%u",ip2long($line_of_text_ip_input[0]));
				
				//Open file and Read first line and ignore it bcoz it is a header of columns.
				$file_handle_ip_range = fopen("ipsubnetoutput.csv", "r");
				$line_of_text_ip_range = fgetcsv($file_handle_ip_range, 2048);

				//Counter for record no.
				$record_no=1;		
				$found=0;
				$count+=1;
				$counter++;

				while (!feof($file_handle_ip_range)) 
				{
					$record_no+=1;
					$line_of_text_ip_range = fgetcsv($file_handle_ip_range, 2048);
					$first_addr = $line_of_text_ip_range[10];	
					$last_addr = $line_of_text_ip_range[11]; 
					//$fip = ip2long($first_addr); 
					//$lip = ip2long($last_addr); 
					$fip = (float)sprintf("%u",ip2long($first_addr));
					$lip = (float)sprintf("%u",ip2long($last_addr));
					
					if(($search_ip>=$fip) && ($search_ip<=$lip))
					{   
						//echo "<h1>Search succesfull</h1>";
						//for($x=0;$x<count($locations);$x++)
						$locations[$line_of_text_ip_range[1]]=$locations[$line_of_text_ip_range[1]]+1;
						$cities[$line_of_text_ip_range[14]]=$cities[$line_of_text_ip_range[14]]+1;
						//echo "<h1>".($locations[$line_of_text_ip_range[1]])."</h1>";
						//echo $line_of_text_ip_range[1]."<br/>key".$locations[$line_of_text_ip_range[1]]."<br/>";
						/*echo "<br/><h2>IP Address:    </b>". long2ip($search_ip) . "</h2>\n"; 
						echo "<br/><b>Division:       </b>". $line_of_text_ip_range[0] . "\n"; 
						echo "<br/><b>Location:       </b>". $line_of_text_ip_range[1] . "\n"; 
						echo "<br/><b>Tower:          </b>". $line_of_text_ip_range[2] . "\n"; 
						echo "<br/><b>Floor:          </b>". $line_of_text_ip_range[3] . "\n"; 
						echo "<br/><b>Wing:           </b>". $line_of_text_ip_range[4] . "\n"; 
						echo "<br/><b>Subnet:         </b>". $line_of_text_ip_range[5] . "\n"; 
						echo "<br/><b>SubnetCat:      </b>". $line_of_text_ip_range[6] . "\n"; 
						echo "<br/><b>Subnet Mask:    </b>". $line_of_text_ip_range[7] . "\n"; 
						echo "<br/><b>Number of Hosts:</b>". $line_of_text_ip_range[8] . "\n"; 
						echo "<br/><b>Host Range:     </b>". $line_of_text_ip_range[9] . "\n"; 
						echo "<br/><b>Found at line:  </b>". $record_no;*/
						
						$values=array(long2ip($search_ip),$line_of_text_ip_range[0],$line_of_text_ip_range[1],$line_of_text_ip_range[2],$line_of_text_ip_range[3],$line_of_text_ip_range[4],$line_of_text_ip_range[5],$line_of_text_ip_range[6],$line_of_text_ip_range[7],$line_of_text_ip_range[8],$line_of_text_ip_range[9],$line_of_text_ip_range[12],$line_of_text_ip_range[13]);
						fputcsv($file_handle_ip_output,$values);
						$found=1;
						break;
					}

				}
				
				if($found==1)
				{
					//echo "<tr><td>".$count."</td><td>". long2ip($search_ip) ."</td><td>Found</td></tr>"; 
					//print "\nFound: ".$search_ip."\t".$fip."\t".$lip;
					$fc+=1;
				}
				else
				{
					//echo "<tr><td>".$count."</td><td>". long2ip($search_ip) ."</td><td>Not Found</td></tr>"; 
					//print "\nNot Found: ".$search_ip."\t".$fip."\t".$lip;
					fputcsv($file_handle_ip_not_found_output, $line_of_text_ip_input);		
					$nfc+=1;
				}

				fclose($file_handle_ip_range);
				$line_of_text_ip_input = fgetcsv($file_handle_ip_input, 4096);
			}

			
			echo "\nFound IPs!: ".$fc;
			echo "\nNot found IPs!: ".$nfc;
			
			array_multisort($locations,SORT_DESC);
			print_r($locations);
			$file_handle_ip_count_location=fopen("found_ip_count_location_alert_unique_ip.csv","w");
			$fields=array("Location","Count","");
			fputcsv($file_handle_ip_count_location,$fields);
			foreach($locations as $key=>$value)
				if($value>0)
				{
					echo "\n".$key."\t".$value."=".$key;
					$values=array($key,$value);
					fputcsv($file_handle_ip_count_location,$values);
				}

			fclose($file_handle_ip_count_location);
			
		?>
		<?php
		//IP Infection by city
			echo "\nLocation\tIPs count\tMap";
			array_multisort($cities,SORT_DESC);
			$cities_geo=array("Bangalore"=>"12.954094298007135, 77.63093949999995","Pune"=>"18.524220910029797, 73.86468890000003",
							  "Chennai"=>"13.034053366154168, 80.20692110000005","Kolkatta"=>"22.676351975142673, 88.36805555000001",
								"Hyderabad"=>"17.408988979962423, 78.4657496499999","Gurgoan"=>"28.424905852627113, 76.98978175000002",
							"Mysore"=>"12.305328412587707, 76.64114689999997","Mumbai"=>"19.082542865129508, 72.88120415000003",
							"Patna"=>"25.60896923054167, 85.16415720000009","Others"=>"9.927339453,	76.2668541","Guwahati"=>"26.151123586398626, 91.71661374999996");
			$file_handle_ip_count_city=fopen("found_ip_count_city_alert_unique_ip.csv","w");
			$fields=array("City","Count","LatLong");
			fputcsv($file_handle_ip_count_city,$fields);
			foreach($cities as $key=>$value)
				if($value>0)
				{
					foreach($cities_geo as $city_name=>$city_geo)
						if($key==$city_name)
						{
						echo "\n".$key."\t".$value."\t".$city_geo;
						$values=array($key,$value,$city_geo);
						fputcsv($file_handle_ip_count_city,$values);
						}
				}

			fclose($file_handle_ip_count_city);
			fclose($file_handle_ip_input);
			fclose($file_handle_ip_output);
		?>
		
