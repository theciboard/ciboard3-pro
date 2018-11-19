<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['email_must_be_array'] = "이메일 유효성 검사는 반드시 배열로 전달되어야 합니다.";
$lang['email_invalid_address'] = "유효하지 않은 이메일 형식입니다 : %s";
$lang['email_attachment_missing'] = "다음 메일의 첨부파일을 찾을 수 없습니다 : %s";
$lang['email_attachment_unreadable'] = "첨부파일을 열 수 없습니다 : %s";
$lang['email_no_from'] = '보내는이(From)가 지정되어있지 않습니다';
$lang['email_no_recipients'] = "받는이(To, Cc, Bcc)가 지정되어있지 않습니다";
$lang['email_send_failure_phpmail'] = "PHP mail() 을 사용하여 메일을 보낼 수 없습니다. 이 서버는 PHP mail() 을 이용해 메일을 보낼 수 있도록 설정되어 있지 않을 수 있습니다.";
$lang['email_send_failure_sendmail'] = "PHP Sendmail 로 메일을 보낼 수 없습니다. 이 서버는 PHP Sendmail 을 이용해 메일을 보낼 수 있도록 설정되어 있지 않을 수 있습니다.";
$lang['email_send_failure_smtp'] = "PHP SMTP 를 통해 메일을 보낼 수 없습니다. 이 서버는 이 방법을 이용해 메일을 보낼 수 있도록 설정되어 있지 않을 수 있습니다.";
$lang['email_sent'] = "메세지는 다음 프로토콜을 이용해 정상 전달되었습니다 : %s";
$lang['email_no_socket'] = "Sendmail 소켓을 열 수 없습니다. 설정을 확인하여 주시기 바랍니다.";
$lang['email_no_hostname'] = "SMTP 호스트네임이 지정되지 않았습니다.";
$lang['email_smtp_error'] = "다음 SMTP 오류가 발생했습니다 : %s";
$lang['email_no_smtp_unpw'] = "에러 : SMTP username 과 password 를 지정해야 합니다.";
$lang['email_failed_smtp_login'] = "AUTH LOGIN 명령을 보내는데 실패했습니다. 에러 : %s";
$lang['email_smtp_auth_un'] = "아이디 인증에 실패하였습니다. 에러 : %s";
$lang['email_smtp_auth_pw'] = "패스워드 인증에 실패하였습니다. 에러 : %s";
$lang['email_smtp_data_failure'] = "데이터를 보낼 수 없습니다 : %s";
$lang['email_exit_status'] = "완료 상태 코드 : %s";
