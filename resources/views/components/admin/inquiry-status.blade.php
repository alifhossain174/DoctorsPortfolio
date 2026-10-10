@props(['status'])
<x-admin.badge :tone="match($status) {'new'=>'warning','confirmed','completed'=>'success','cancelled','spam'=>'danger',default=>'neutral'}">{{ ucfirst($status) }}</x-admin.badge>
