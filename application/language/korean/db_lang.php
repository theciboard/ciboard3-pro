<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['db_invalid_connection_str'] = '지정된 연결 문자열은 데이터베이스 설정 확인이 불가능합니다';
$lang['db_unable_to_connect'] = '제공된 설정으로 데이터베이스에 연결이 불가능합니다';
$lang['db_unable_to_select'] = '지정된 데이터베이스를 선택할 수 없습니다: %s';
$lang['db_unable_to_create'] = '지정된 데이터베이스를 생성할 수 없습니다: %s';
$lang['db_invalid_query'] = '전송된 쿼리가 올바르지 않습니다';
$lang['db_must_set_table'] = '전송된 쿼리를 사용하기 위해서는 데이터베이스 테이블을 지정해야 합니다';
$lang['db_must_use_set'] = 'UPDATE 쿼리를 사용하기 위해서는 "set" 메소드를 사용해야 합니다';
$lang['db_must_use_index'] = '일괄 업데이트를 위해서는 일치하는 인덱스를 지정해야 합니다';
$lang['db_batch_missing_index'] = '일괄 업데이트를 위해 제출한 1개 또는 그 이상의 행 인덱스가 없습니다';
$lang['db_must_use_where'] = 'UPDATE 쿼리에는 where 구문이이 필요합니다';
$lang['db_del_must_use_where'] = 'DELETE 쿼리에는 "where" 구문이나 "like" 구문이 필요합니다';
$lang['db_field_param_missing'] = '필드 이름을 제거 하려면 테이블 이름을 매개변수로 지정해야 합니다';
$lang['db_unsupported_function'] = '이 기능은 해당 데이터베이스에서 사용할 수 없습니다';
$lang['db_transaction_failure'] = '트랜젝션이 실패했습니다: 롤백이 실행되었습니다';
$lang['db_unable_to_drop'] = '지정한 데이터베이스를 삭제할 수 없습니다';
$lang['db_unsuported_feature'] = '현재 사용하고 있는 데이터베이스 플랫폼에서 지원되지 않는 기능입니다';
$lang['db_unsuported_compression'] = '현재 서버에서 선택한 파일 압축 포맷은 지원하지 않습니다';
$lang['db_filepath_error'] = '지정한 파일 경로에 데이터를 쓸 수 없습니다';
$lang['db_invalid_cache_path'] = '지정한 캐시 경로가 잘못되었거나 쓰기 가능하지 않습니다';
$lang['db_table_name_required'] = '테이블 이름이 필요합니다';
$lang['db_column_name_required'] = '컬럼 이름이 필요합니다';
$lang['db_column_definition_required'] = '컬럼 정의가 필요합니다';
$lang['db_unable_to_set_charset'] = '클라이언트 연결 문자 인코딩을 설정할 수 없습니다 : %s';
$lang['db_error_heading'] = '데이터베이스 오류가 발생하였습니다';
