#!/usr/bin/python
# -*- coding: utf-8 -*-

import sys
from pprint import pprint

# ���������� top.mail.ru
from top_mail_ru import TopMailRu

EMAIL='__MY__EMAIL__'
PASSWORD='__MY__PASSWORD__'
MY_SITE='http://__MY__SITE__NAME__'
# ���� � API, ��������� ��� ����������� ������.
# ��� ��������� ����� �������� ��� �� https://top.mail.ru/feedback
# ��. https://help.mail.ru/top/API/main
API_KEY='__API__KEY__'

# 1-� �������� - API key
tmr = TopMailRu(API_KEY)

# registerSite() - ����������� �����
# ��. https://help.mail.ru/top/API/main - ����������� ����� � ����������
result = tmr.registerSite({
        'title' : 'my site', # �������� ������ ��������
        'url' : MY_SITE, # url ������� ��� ������� ����� ��������
        'email' : EMAIL, # email
        'password' : PASSWORD, # ������
        'public' : 0, # 1 - ������� ���������, 0 - ����� ��������
        'rating' : 0, # 1 - ������� ��������� � �������� - 1, 0 - �� ����������
        # id ���������, 0 - ��� ���������.
        # ��� ��������� ������ ��������� ����� ��������������� /json/categories,
        # �����������, ��������� � ������ ������� � �������� (rating: 1).
        # ��� ������� � �������� �������� �������� ���������� ��� ������ ������� ��������� ��������@Mail.ru.
        # ���������� ����� ��������� ��������� ��� ���������� ������ ������� ��������� �� ������� ��������@Mail.ru.
        # � ����� ������� �������� ������������ ������ ������� � ������������� �������� �����������.
        # ��. https://help.mail.ru/top/API/response - ���������� � ���������� ��������
        'category' : 0
        })
if 'error' in result:
    print('registerSite(), error')
    pprint(result)
    sys.exit(1)

counter_id = result['id']

# login() - ������������������ �� ������
# 1-� �������� - id ��������
# 2-� �������� - ������ � ��������
result = tmr.login(counter_id, PASSWORD);
if not result:
    print('login() error')
    sys.exit(1)

# getCode() - �������� ��� ��������
# 1-� �������� id ��������
# 2-� �������� - ������, ���� �� ����� � ������� ������� login/loginByHash, �� �������� ����� �������� ������
# 3-� �������� - ����� (��. https://help.mail.ru/top/API/main ��� ��������)
result = tmr.getCode(counter_id, '', { 'mode' : 'nologo', 'pagetype' : 'xhtml' })
if 'error' in result:
    print('getCode() error')
    pprint(result)
    sys.exit(1)

print('Code')
pprint(result)

# getStat() - �������� ������ ������
# 1-� �������� - id ��������
# 2-� �������� - ������, ���� �� ����� � ������� ������� login/loginByHash, �� �������� ����� �������� ������
# 3-� �������� - ��� ������ (��. https://help.mail.ru/top/API/response)
# 4-� �������� - ��������� ���������� ����������
# ����� �������� ��. ��������� �������� (https://help.mail.ru/top/API/params) �
# �������� JSON ������� (https://help.mail.ru/top/API/response)
result = tmr.getStat(counter_id, PASSWORD, 'visits', { 'period' : 1 })
if 'error' in result:
    print('getStat(), error')
    pprint(result)
    sys.exit(1)

print('Counter ' + counter_id + ', visits')
# ��������� ������� �� ���� ������ (��. https://help.mail.ru/top/API/response)
pprint(result)
