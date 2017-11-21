<?php

   /*
    *        Adaline
    *------------------------- */
    
    /* PHP-ML - Machine Learning library for PHP */
    
    class Adaline {

    protected $weights; // Entradas 
    protected $learning_rate = 0.01; // Taxa de Aprendisado Linear
    protected $learning_rate2 = 0.1; // Taxa de Aprendisado Não Linear
    protected $result = 0; // resulte
    protected $erro; // erro
    protected $erro2; // erro
    protected $cErro;

    
    
    public function __construct(){
  
    }

    
    public function trainLinear($input,$output, $epooc){
        
        
         $input2; // Incluindo bias aos dados
         $sum = 0;       // Somatorio das entradas + bias
         $delta = 0;     // função delta (sidaesperada - somatorio)
        
         for ($i = 0; $i < count($input); $i++) {
             for ($j = 0; $j < count($input[0]) + 1; $j++) {
                  if($j == 0){
                       $input2[$i][$j] = 1;
                  }else{
                       $input2[$i][$j] = $input[$i][$j-1];
                  }
             }
         }
         
         $this->generate_weights(count($input2[0])); // Gerando Pesos 
         
         for ($k = 0; $k < $epooc; $k++) {
             
             for ($i = 0; $i < count($input2); $i++) {
                 
                  for ($j= 0; $j < count($input2[0]); $j++) {
                      
                       $sum += $this->weights[$j] * $input2[$i][$j]; // somatorio peso * bias * entradas
                  }
                  
                  
                  $delta = ($output[$i] - $sum); // Calculo do erro 
                 // print_r($output[$i] . " - " .$sum);
                  $erro[$k] =  $erro[$k] +  pow($delta,2); 
                  
            /*------------Ajuste de Pesos---------------*/
                for ($j = 0; $j < count($this->weights); $j++) {
                    
                     $this->weights[$j] = $this->weights[$j] + $this->learning_rate * $delta * $input2[$i][$j];
                }
                
            /*--Set variaves de manipulação--*/
                $sum = 0;
                $delta = 0;
                  
             }
             
             	
             //	printf ("Erro: ".$k." = "."%6.10f",$erro[$k] / count($input2));
             //	print_r('<br>');
              
         }  
          
    }
  
    public function trainNaoLinear ($input,$output, $epooc){
        
         $input2; // Incluindo bias aos dados
         $sum = 0;       // Somatorio das entradas + bias
         $sig = 0;     // função sigmoid (1 / (1 + exp(-x)))
         $delta = 0;     // função delta (sidaesperada - somatorio)
        
         for ($i = 0; $i < count($input); $i++) {
             for ($j = 0; $j < count($input[0]) + 1; $j++) {
                  if($j == 0){
                       $input2[$i][$j] = 1;
                  }else{
                       $input2[$i][$j] = $input[$i][$j - 1];
                  }
             }
         }
         
         $this->generate_weights(count($input2[0])); // Gerar Pesos 

         for ($k = 0; $k < $epooc; $k++) {
             
             for ($i = 0; $i < count($input2); $i++) {
                 
                  for ($j= 0; $j < count($input2[0]); $j++) {
                      
                       $sum += $this->weights[$j] * $input2[$i][$j]; // somatorio peso * bias * entradas
                  }
                  
                  
                  $sig = 1/(1 + exp(-$sum)); // calculo sigmoid
                 
                  $delta = ($output[$i] - $sig); // Calculo do erro 
                  
                  $erro2[$k] = $erro2[$k] + pow($delta,2);
                  
            /*------------Ajuste de Pesos---------------*/
                for ($j = 0; $j < count($this->weights); $j++) {
                    
                     $this->weights[$j] = $this->weights[$j] + $this->learning_rate2 * $input2[$i][$j] * $delta * $sig * (1 - $sig);
                }
                
            /*--Set variaves de manipulação--*/
                $sum = 0;
                $sig = 0;
                $delta = 0;
             }
             
             //	printf ("Erro: ".$k." = "."%6.10f",$erro2[$k] / count($input2));
             //	print_r('<br>');
              
         } 
         
    }
    
    public function testAdaline($input,$data, $output){
        
         $input2; // Incluindo bias aos dados
         $sum = 0;  // Somatorio das entradas + bias
         $result;
         $cErro=0;

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
                      
                 $sum += $this->weights[$j] * $input2[$i][$j];
             }
             
             //	print_r("Resutado: ".$sum." => Sainda desejada: ".$output[$i].'<br>');
             
            $delta = ($output[$i] - $sum); // Calculo do erro 
             // print_r($output[$i] . " - " .$sum);
            $cErro = $cErro + pow($delta,2); 
             
            $result[$i] =  round($sum, 3);
             	
              ///  print_r("Data ".$data[$i]." Resutado: ".round($sum, 4).'<br>');
             	
            $sum = 0;
        }
        
         echo '<br>#Erro quadratico medio rede linear =>'.$cErro / count($input2);
         
         return $result;
        
    }
    
     public function testAdaline2($input,$output){
        
         $input2; // Incluindo bias aos dados
         $sum = 0;  // Somatorio das entradas + bias
         $result;
         $cErro=0;

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
                      
                 $sum += $this->weights[$j] * $input2[$i][$j];
             }
 
                $sig = 1/(1 + exp(-$sum));
              
                $delta = ($output[$i] -  $sig); // Calculo do erro 
                // print_r($output[$i] . " - " .$sum);
                $cErro = $cErro + pow($delta,2); 

                $result[$i] =  $sig;
                
               // print_r("Resutado: ". $sig ." => Sainda desejada: ".$output[$i].'<br>');
             	
             	$sum = 0;
        }
           echo '<br>#Erro quadratico medio rede não linear =>'.$cErro / count($input2) .'<br>';
           
           return $result;
         
        
    }
    
    public function generate_weights($index){
        for($i = 0; $i < $index; $i++){
            $this->weights[$i] = (float)rand()/(float)getrandmax();
            
           // print_r($this->weights[$i]);
        }
    }
}
?>