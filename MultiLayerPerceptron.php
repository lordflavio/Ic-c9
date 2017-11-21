<?php
   /*
    * MultiLayer Perceptron
    *------------------------- */
    
    /* PHP-ML - Machine Learning library for PHP */
    
    class MultiLayerPerceptron{

    protected $input; // Entradas 
    protected $output; // Saidas 
    protected $hiddenNeurons; // camadas 
    protected $alfa; // função alfa
    protected $inputW; // Pesos das entradas 
    protected $outputW; // Pesoas das saidas
    protected $biaInputW; // bias da entrada
    protected $biasOutputW; // bias da saida 
    protected $erroVAl; // erros 
    
    
    public function __construct( $input,$output,$hiddenNeurons,$alfa){
        
        //echo "it works";
             
         $this->input = $input;
         $this->output = $output;
         $this->hiddenNeurons = $hiddenNeurons;
         $this->alfa = $alfa;
         
         $this->generate_weights();

    }
    
    public function generate_weights(){
        
          
        for($i = 0; $i < count($this->input); $i++){
            for($j = 0; $j < $this->hiddenNeurons; $j++){
                //$inputW[$i][$j] = (float)rand()/(float)getrandmax();
                $this->inputW[$i][$j] = (float)rand()/(float)getrandmax();
            }
        }
        
        //print_r($this->inputW);
        
        for ($j = 0; $j < $this->hiddenNeurons; $j++) {
              //$outputW[$j][0] = (float)rand()/(float)getrandmax();
              $this->outputW[$j][0] = (float)rand()/(float)getrandmax();
        }
        
        //print_r($this->outputW);
        
        for ($k = 0; $k < $this->hiddenNeurons; $k++) {
             //4biasInputW[$k] = (float)rand()/(float)getrandmax();
              $this->biasInputW[$k] = (float)rand()/(float)getrandmax();
        }
        
        //print_r($this->biasInputW);
        
        //$biaOutputW[0] = (float)rand()/(float)getrandmax();
        $this->biaOutputW[0] = (float)rand()/(float)getrandmax();
        
        //print_r($this->biaOutputW);
        
    }
    
    public function valid (){
        $sum;
        $sumOut;
        $erro;
        $erroTotal;
        
        for($i = 0; $i < count($this->input); $i++){
            
            for($j = 0; $j < $this->hiddenNeurons; $j++){
                
                for($k = 0; $k < count($this->input[0]); $k++){
                    
                    $sum[$j] += $this->inputW[$k][$j]*$this->input[$i][$k];
                     print_r(exp(2));
                }
                $sum[$j] += $this->biasInputW[$j];
                $sum[$j] = 1/(1+exp(-$sum[$j]));
            }
            
            
            for ($k = 0; $k < 1; $k++) {
                
                for ($j = 0; $j < $this->hiddenNeurons; $j++) {
                     $sumOut[$k] += $this->OutputW[$k]* $sum[$j];
                }
                
                $sumOut[$k] += $this->biaOutputW[$k];
                $erro[$i] = $this->output - $sumOut[$k];
                
                $erroTotal += pow($erro[$i],2); 
            }
            
            $sumOut[0] = 0;
            
            for ($j = 0; $j < $this->hiddenNeurons; $j++) {
                 $sum[$j] = 0;
            }
            
        }
        
        return $erroTotal/ count($this->input);
        
    }
    
    
    public function train ($interacoes){
        $erroval;
        $sum;
        $sumOut;
        $erro;
        $gradienteOut;
        $gradienteH;
        $erroTotal;
        
        for ($in = 0; $in < $interacoes; $in++) {
            
            for ($i = 0; $i <  count($this->input); $i++) {
            
                for ($j = 0; $j < $this->hiddenNeurons; $j++) {
                    
                    for ($k = 0; $k < count($this->input[0]); $k++) {
                        
                        $sum[$j] += $this->inputW[$k][$j] * $this->input[$i][$k];
                    }
                    
                    $sum[$j] +=  $this->biaInputW[$j];
                    $sum[$j] = 1/(1+exp(-$sum[$j]));
                }
                
                for ($k = 0; $k < 1; $k++) {
                    
                    for ($j = 0; $j < $this->hiddenNeurons; $j++) {
                         $sumOut[$k] += $this->OutputW[$k] * $sum[$j];
                    }
                    
                    $sumOut[$k] += $this->biaOutputW[$k];
                    $erro[$i] = $this->output - $sumOut[$k];
                    
                    $erroTotal[$in] += pow($erro[$i],2); 
                }
                
                for ($k = 0; $k < 1; $k++) {
                    $gradienteOut[$k] = 1*$erro[$k];
                }
                
                for ($j = 0; $j < $this->hiddenNeurons; $j++) {
                     
                     for ($k = 0; $k < 1; $k++) {
                          $gradienteH[$j] += $this->outputW[$j][$k] * $gradienteOut[$k];
                     }
                     
                     $gradienteH[$j] *= $sum[$j] * (1 -$sum[$j]);
                }
                
                
                /* AJUSTE DE PESSOS */
                
                for ($j = 0; $j < $this->hiddenNeurons; $j++) {
                    
                     for ($k = 0; $k < 1; $k++) {
                         
                          $this->outputW[$j][$k] += $this->alfa * $sum[$j] * $gradienteOut[$k];
                     }
                     
                     for ($z = 0; $z < count($this->input[0]); $z++) {
                          
                          $this->inputW[$z][$j] += $this->alfa * $this->input[$z][$j] * $gradienteH[$j];
                     }
                }
                
                  /* AJUSTE DE BIAS */
                  
                  for ($k = 0; $k < 1; $k++) {
                      
                      $this->biasOutputW[$k] += $this->alfa * 1 * $gradienteOut[$k];
                  }
                  
                  for ($j = 0; $j < $this->hiddenNeurons; $j++) {
                       
                       $this->biaInputW[$j] += $this->alfa * 1 * $gradienteH[$j];
                  }
                  
                   /* RESETAR */
                  
                  for($j = 0; $j < 1; $j++){
					$sumOut[$j]=0;
					$gradienteOut[$j]=0;
					
				  }
				
				  for( $k = 0; $k < $this->hiddenNeurons; $k++){
					
					$sum[$k]=0;
					$gradienteH[$k]=0;
					
				 }
                
                
            }
            
            $this->erroVAl[$in] = valid($this->input, $this->output);
            
            $erroTotal[$in] =  $erroTotal[$in] /count($this->input);
            
            print_r("Erro: ".$in." = ".$erroTotal[$in].'<br>');
            
        }
        
        return $erroTotal;
    }
    
    public function testValue(){
        
        print_r($this->inputW);
        print_r($this->outputW);
        print_r($this->biasInputW);
        print_r($this->biaOutputW);
         /*
         print_r($this->inputW .'\n');
         print_r($this->outputW .'\n');
         print_r($this->biasInputW .'\n');
         print_r($this->biaOutputW .'\n');
         */
    }
}
?>