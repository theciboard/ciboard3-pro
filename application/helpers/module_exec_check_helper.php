<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Module exec helper
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 인증, 결제 모듈 실행 체크
 */
if ( ! function_exists('module_exec_check')) {
	function module_exec_check($type)
	{
		if ( ! $type) return;

		// kcb일 때
		if ($type == 'kcb') {
			// 실행모듈
			if(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
				if(PHP_INT_MAX == 2147483647) // 32-bit
					$exe = FCPATH . 'plugin/selfcert/kcb/bin/okname';
				else
					$exe = FCPATH . 'plugin/selfcert/kcb/bin/okname_x64';
			} else {
				if(PHP_INT_MAX == 2147483647) // 32-bit
					$exe = FCPATH . 'plugin/selfcert/kcb/bin/okname.exe';
				else
					$exe = FCPATH . 'plugin/selfcert/kcb/bin/oknamex64.exe';
			}
		}

		// kcp일 때	 ( 본인인증 )
		if ($type == 'kcp') {
			if(PHP_INT_MAX == 2147483647) // 32-bit
				$exe = FCPATH . 'plugin/selfcert/kcp/bin/ct_cli';
			else
				$exe = FCPATH . 'plugin/selfcert/kcp/bin/ct_cli_x64';
		}

		// LG의 경우 log 디렉토리 체크
		if ($type == 'lg') {
			return;
		}

		if( $type == 'pg_kcp' ){		//kcp 결제
			if(PHP_INT_MAX == 2147483647) // 32-bit
				$exe = FCPATH . 'plugin/pg/kcp/bin/pp_cli';
			else
				$exe = FCPATH . 'plugin/pg/kcp/bin/pp_cli_x64';
		}

		$error = '';
		$is_linux = false;
		if(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
			$is_linux = true;

		// 모듈 파일 존재하는지 체크
		if(!is_file($exe)) {
			$error .= $exe.' 파일이 존재하지 않습니다.';
		} else {
			// 실행권한 체크
			if(!is_executable($exe)) {
				if($is_linux)
					$error .= $exe.'\n파일의 실행권한이 없습니다.\n\nchmod 755 '.basename($exe).' 과 같이 실행권한을 부여해 주십시오.';
				else
					$error .= $exe.'\n파일의 실행권한이 없습니다.\n\n'.basename($exe).' 파일에 실행권한을 부여해 주십시오.';
			} else {
				// 바이너리 파일인지
				if($is_linux) {
					$search = false;
					$isbinary = true;
					$executable = true;

					switch($type) {
						case 'kcp':
							exec($exe.' -h 2>&1', $out, $return_var);

							if($return_var == 139) {
								$isbinary = false;
								break;
							}

							for($i=0; $i<count($out); $i++) {
								if(strpos($out[$i], 'KCP ENC') !== false) {
									$search = true;
									break;
								}
							}
							break;
						case 'pg_kcp':
							exec($exe.' -h 2>&1', $out, $return_var);

							if($return_var == 139) {
								$isbinary = false;
								break;
							}

							for($i=0; $i<count($out); $i++) {
								if(strpos($out[$i], 'CLIENT') !== false) {
									$search = true;
									break;
								}
							}
							break;
						case 'kcb':
							exec($exe.' D 2>&1', $out, $return_var);

							if($return_var == 139) {
								$isbinary = false;
								break;
							}

							for($i=0; $i<count($out); $i++) {
								if(strpos(strtolower($out[$i]), 'ret code') !== false) {
									$search = true;
									break;
								}
							}
							break;
					}

					if(!$isbinary || !$search) {
						$error .= $exe.'\n파일을 바이너리 타입으로 다시 업로드하여 주십시오.';
					}
				}
			}
		}

		return $error;
	}
}
//end if function exists

if ( ! function_exists('pg_module_exec_check')) {

	// 결제 셋팅들을 검사합니다.

	function pg_module_exec_check($type)
	{
		$errors = array();

		switch ($type) {
			case 'kcp' :

				if(!extension_loaded('openssl')) {
					$errors[] = "PHP openssl 확장모듈이 설치되어 있지 않습니다.\n모바일 쇼핑몰 결제 때 사용되오니 openssl 확장 모듈을 설치하여 주십시오.";
				}

				if(!extension_loaded('soap') || !class_exists('SOAPClient')) {
					$errors[] = "PHP SOAP 확장모듈이 설치되어 있지 않습니다.\n모바일 쇼핑몰 결제 때 사용되오니 SOAP 확장 모듈을 설치하여 주십시오.";
				}

				if( $err_msg = module_exec_check('pg_kcp') ){
					$errors[] = $err_msg;
				}

				break;
		}

		return $errors;
	}
}	//end if function exists