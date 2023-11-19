<x-mail::message>
# Introduction

Hello Admin. 

{{ $user_name }} of company {{ $company_name }} have uploaded {{ $doc_type }} <b>{{ $page }}</b>document.

Regards,<br>
{{ config('app.name') }}
</x-mail::message>
