<?php
function alt_mcrypt_create_iv ($size) {
   $iv = '';
   for($i = 0; $i < $size; $i++) {
       $iv .= chr(rand(0,255));
   }
   return $iv;
}

function encrypt($input) {
	$key = "spluniaphoafoaniugoawlufleqoayiasplubrluml1tiuwriUtriaw";
	$size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
	$iv = alt_mcrypt_create_iv($size);
	$baseiv = base64_encode($iv);
	$encrypted_data = mcrypt_cbc(MCRYPT_BLOWFISH, $key, $input, MCRYPT_ENCRYPT, $iv);
	$store = $baseiv . base64_encode($encrypted_data);

	return $store;
}
?>