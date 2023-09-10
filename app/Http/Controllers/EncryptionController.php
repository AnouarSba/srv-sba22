<?php

namespace App\Http\Controllers;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\VarDumper\Cloner\Data;

class EncryptionController extends Controller
{
   


    private static function getPrivateKey() 
    {        
        $abs_path = Storage::path('private.pem');
        $content = file_get_contents($abs_path);    
        return openssl_pkey_get_private($content);    
    }    

    /**     
     * Get public key     
     * @return bool|resource     
     */    
    private static function getPublicKey()
    {   
        $abs_path = Storage::path('public.pem');
        $content = file_get_contents($abs_path);    
        return openssl_pkey_get_public($content);     
    }

    /**     
     * Private key encryption     
     * @param string $data     
     * @return null|string     
     */    
    public static function privEncrypt($data)    
    {      
        if (!is_string($data)) {            
            return null;        
        } 

        $tok = 'etuspayaissadadoetuspayaissadado';
        $iv = 'etuspayaissadado';

        $cipher = "aes-256-cbc";

        $key = $tok; // 32 chars
        $iv = $iv; // 16 chars
          
        $encryption_key = $key;  
        $encrypted_data = openssl_encrypt($data, $cipher, $encryption_key, 0, $iv);   
        return $encrypted_data;
    }    

    /**     
     * Public key encryption     
     * @param string $data     
     * @return null|string     
     */    
    public static function publicEncrypt($data = '')   
    {        
        if (!is_string($data)) {            
            return null;        
        }        
        return openssl_public_encrypt($data,$encrypted,self::getPublicKey()) ? base64_encode($encrypted) : null;    
    }    

    /**     
     * Private key decryption     
     * @param string $encrypted     
     * @return null     
     */    
    public static function privDecrypt($encrypted = '')    
    {        
        if (!is_string($encrypted)) {            
            return null;        
        }        
        return (openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey())) ? $decrypted : null;    
    }    

    /**     
     * Public key decryption     
     * @param string $encrypted     
     * @return null     
     */    
    public static function publicDecrypt($token)    
    {      
        if (!is_string($token)) {            
            return null;        
        }   
        $tok = 'etuspayaissadadoetuspayaissadado';
        $iv = 'etuspayaissadado';
        $cipher = "aes-256-cbc";
        $decrypted_data = openssl_decrypt($token, $cipher, $tok, 0, $iv);    
        return   $decrypted_data;
    }

}

