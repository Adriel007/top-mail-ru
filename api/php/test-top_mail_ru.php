<?php

// ���������� top.mail.ru
include_once __DIR__ . '/top_mail_ru.php';

$EMAIL = '__MY__EMAIL__@my.com';
$PASSWORD = '__MY__PASSWORD__';
$MY_SITE = 'http://__MY__SITE__NAME__.domain.3';
// ���� � API, ��������� ��� ����������� ������.
// ��� ��������� ����� �������� ��� �� https://top.mail.ru/feedback
// ��. https://help.mail.ru/top/API/main
$API_KEY = '__API__KEY__';

// 1-� �������� API KEY
// ���� � API, ��������� ��� ����������� ������.
// ��� ��������� ����� �������� ��� �� https://top.mail.ru/feedback
// ��. https://help.mail.ru/top/API/main
$tmr = new TopMailRu($API_KEY, false);

// registerSite() - ����������� �����
// �.�. https://help.mail.ru/top/API/main - ����������� ����� � ����������
$result = $tmr->registerSite(array(
    'title' => 'my site', // �������� ������ ��������
    'url' => $MY_SITE, // url ������� ��� ������� ����� ��������
    'email' => $EMAIL, // email
    'password' => $PASSWORD, // ������
    'public' => 0, // 1 - ������� ���������, 0 - ����� ��������
    'rating' => 0, // 1 - ������� ��������� � �������� - 1, 0 - �� ����������
    // id ���������, 0 - ��� ���������.
    // ��� ��������� ������ ��������� ����� ��������������� /json/categories,
    // �����������, ��������� � ������ ������� � �������� (rating: 1).
    // ��� ������� � �������� �������� �������� ���������� ��� ������ ������� ��������� ��������@Mail.ru.
    // ���������� ����� ��������� ��������� ��� ���������� ������ ������� ��������� �� ������� ��������@Mail.ru.
    // � ����� ������� �������� ������������ ������ ������� � ������������� �������� �����������.
    // ��. https://help.mail.ru/top/API/response - ���������� � ���������� ��������
    'category' => 0
));
if (array_key_exists('error', $result)) {
    echo 'registerSite() error';
    print_r($result);
    exit(1);
}

$counterId = $result->id;

// login() - ������������������ �� ������
// 1-� �������� - id ��������
// 2-� �������� - ������ � ��������
if (!$tmr->login($counterId, $PASSWORD)) {
    echo 'login() error';
    exit(1);
}

// getCode() - �������� ��� ��������
// 1-� �������� id ��������
// 2-� �������� - ������, ���� �� ����� � ������� ������� login/loginByHash, �� �������� ����� �������� ������
// 3-� �������� - ����� (��. https://help.mail.ru/top/API/main ��� ��������)
$result = $tmr->getCode($counterId, $PASSWORD, array(
	'mode' => 'nologo',
	'pagetype' => 'xhtml'
));
if (array_key_exists('error', $result)) {
    echo 'getCode() error';
    print_r($result);
    exit(1);
}

echo 'Code';
print_r($result);

// getStat() - �������� ������ ������
// 1-� �������� - id ��������
// 2-� �������� - ������, ���� �� ����� � ������� ������� login/loginByHash, �� �������� ����� �������� ������
// 3-� �������� - ��� ������ (��. https://help.mail.ru/top/API/response)
// 4-� �������� - ��������� ���������� ����������
// ����� �������� ��. ��������� �������� (https://help.mail.ru/top/API/params) �
// �������� JSON ������� (https://help.mail.ru/top/API/response)
$tmr->getStat($counterId, $PASSWORD, 'visits', array(
	'period' => 1,
));
if (array_key_exists('error', $result)) {
    echo 'getStat(), error';
    print_r($result);
    exit(1);
}

echo 'Visits';
print_r($result);
