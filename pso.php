<?php

   /*
    *        Adaline
    *------------------------- */
    
    /* PHP-ML - Machine Learning library for PHP */
    
    //https://www.filipeflop.com/blog/projeto-robo-seguidor-de-linha-arduino/
    
    class pso {

    protected $populacao; // população
    protected $fitness; // fitness
    protected $erro;   // erro
    protected $interacoes; // interações 
    protected $pBest; 
    protected $gBest;
    
   protected $input2; // Incluindo bias aos dados
   protected $output;
   
   
    
    
        public function __construct($input,$output, $epooc){
            
            $this->interacoes = $epooc;
            $this->output = $output;
            
            for ($i = 0; $i < count($input); $i++) {
                 for ($j = 0; $j < count($input[0]) + 1; $j++) {
                      if($j == 0){
                           $this->input2[$i][$j] = 1;
                      }else{
                            $this->input2[$i][$j] = $input[$i][$j-1];
                      }
                 }
             }
             
             
            

               $this->population(30, count($this->input2[0])); // Gerando a população
               
               $this->train(); // treinamento 
               
               for ($i = 0; $i < count($this->input2[0]); $i++) {
                    echo " Peso usado => ". $this->gBest[0][$i];
               }
              
              
        }
    
        public function population ($size,$pop_size){
            
            for ($i = 0; $i < $size; $i++) {
                 for($j = 0; $j < $pop_size; $j++){
                     
                    $this->populacao[$i][$j] = ( (float)rand()/(float)getrandmax() );
                
                   //  echo $this->populacao[$i][$j] .'<br>';
                 
                   $this->pBest[$i][$j] = $this->populacao[$i][$j];
                 }
            }
            
           // echo count($this->populacao).'<br>';
           // echo count($this->populacao[0]).'<br>';

        }
            
           
       /*     
             for ($i = 0; $i < count( $this->gBest); $i++) {
                 for($j = 0; $j < count( $this->gBest[0]); $j++){
                     
                  echo $this->gBest[$i][0] .'<br>';
                  
                 }
            }*/
            

        
        public function train (){
            
            
            $in = 0;
            
            for ($n = 0; $n < $this->interacoes; $n++) {
            
                 $this->calc_fitness();
                 $this->population_ajust();
                 
               
         //  echo '-------------------------------------------------------------------------- <br>';
           
        }
            
        }

      public function calc_fitness (){
          
          $menor = 99999999;

            for ($k = 0; $k < count($this->populacao); $k++) {
            
                 for ($i = 0; $i < count($this->input2); $i++) {
                         
                    for ($j= 0; $j < count($this->input2[0]); $j++) {
                              
                        $sum += $this->populacao[$k][$j] * $this->input2[$i][$j]; // somatorio peso * bias * entradas
                     //   echo 'Peso => '.$this->populacao[$k][$j].'<br>';
                    }
                    
                  //    echo '================================= <br>';
                 
                      $delta = ($output[$i] - $sum); // Calculo do erro 
                      $erro =  $erro +  pow($delta,2);
                 }
                 
                  if(isset($this->fitness[$k])){
                          if($erro <  $this->fitness[$k] ){
                              for ($i = 0; $i < count($this->populacao[0]); $i++) {
                                     $this->pBest[$k][$i] = $this->populacao[$k][$i];
                              }
                               $this->fitness[$k] = ($erro / count($this->input2)); 
                          }else{
                              
                             for ($i = 0; $i < count($this->populacao[0]); $i++) {
                                  $this->populacao[$k][$i] = $this->pBest[$k][$i]; 
                              }
                              
                          }
                      }else{
                         $this->fitness[$k] = ($erro / count($this->input2)); 
                      }
                     
                     
                      for ($i = 0; $i < count($this->populacao[0]); $i++) {
                         
                           //  echo 'Pessos Usados'.$this->populacao[$k][$i].'<br>';
                      }
                      
                   //  echo '<br> #fitness '. $this->fitness[$k].'<br><br>';
                             
                   /// echo '=============================== <br>';
                 
                  $sum = 0;
                  $delta = 0;
                  $erro = 0;
                 
            } 
            
             for ($y = 0; $y < count($this->fitness); $y++) {
                
                if($this->fitness[$y] < $menor){
                    
                    $menor = $this->fitness[$y]; 
                    
                     for ($i = 0; $i <  count($this->input2[0]); $i++) {
                      $this->gBest[0][$i] =  $this->populacao[$y][$i];
                  }
                }
            }

        }
    
        public function population_ajust (){
            for ($i = 0; $i < count($this->populacao); $i++) {
                for ($j = 0; $j < count($this->populacao[0]); $j++) {
                  //  echo "Antes=> ".$this->populacao[$i][$j];
                    $this->populacao[$i][$j] =  $this->populacao[$i][$j] + (
                                               2 * ((float)rand()/(float)getrandmax()) * ($this->pBest[$i][$j] - $this->populacao[$i][$j]) + 
                                               2 * ((float)rand()/(float)getrandmax()) * ($this->gBest[0][$j] - $this->populacao[$i][$j]) ); 
                  // echo "  Depois=> ".$this->populacao[$i][$j].'<br>';              
                }
            }
        }
        
        
          public function useAdaline($input,$data){
        
         $input2; // Incluindo bias aos dados
         $sum = 0;  // Somatorio das entradas + bias
         $result;

         for ($i = 0; $i < count($input); $i++) {
             
             for ($j = 0; $j < count($input[0]) + 1; $j++) {
                  if($j == 0){
                      $input2[$i][$j] = 1;
                  }else{
                     $input2[$i][$j] = $input[$i][$j-1];
                  }
             }
             
            
         }
         
        for ($i = 0; $i < count($input2); $i++) {
                 
             for ($j= 0; $j < count($input2[0]); $j++) {
                      
                 $sum += $this->gBest[0][$j] * $input2[$i][$j];
                 
                 //echo $input2[$i][$j].'<br>';
             }
             
             //	print_r("Resutado: ".$sum." => Sainda desejada: ".$output[$i].'<br>');
             
             $result[$i] =  round($sum, 3);
             	
                print_r("Data ".$data[$i]." Resutado: ".round($sum, 3).'<br>');
             	
             	$sum = 0;
        }
         
         return $result;
    }
}

?>