{{--
    Status Badge
    Usage: <x-admin.status-badge :status="$model->status" />
    or:    <x-admin.status-badge status="active" />
    or:    <x-admin.status-badge status="paid" size="sm" />
--}}
@props([
    'status' => 'active',
    'size'   => '',
])

@php
$s = strtolower(trim($status ?? 'inactive'));
$map = [
    'active'       => ['cls' => 'badge-active',    'label' => 'Active'],
    'inactive'     => ['cls' => 'badge-inactive',  'label' => 'Inactive'],
    'approved'     => ['cls' => 'badge-approved',  'label' => 'Approved'],
    'pending'      => ['cls' => 'badge-pending',   'label' => 'Pending'],
    'rejected'     => ['cls' => 'badge-rejected',  'label' => 'Rejected'],
    'draft'        => ['cls' => 'badge-draft',     'label' => 'Draft'],
    'featured'     => ['cls' => 'badge-featured',  'label' => 'Featured'],
    'paid'         => ['cls' => 'badge-paid',      'label' => 'Paid'],
    'unpaid'       => ['cls' => 'badge-unpaid',    'label' => 'Unpaid'],
    'true'         => ['cls' => 'badge-active',    'label' => 'Yes'],
    'false'        => ['cls' => 'badge-inactive',  'label' => 'No'],
    '1'            => ['cls' => 'badge-active',    'label' => 'Yes'],
    '0'            => ['cls' => 'badge-inactive',  'label' => 'No'],
    'verified'     => ['cls' => 'badge-approved',  'label' => 'Verified'],
    'unverified'   => ['cls' => 'badge-inactive',  'label' => 'Unverified'],
    'published'    => ['cls' => 'badge-active',    'label' => 'Published'],
    'unpublished'  => ['cls' => 'badge-draft',     'label' => 'Unpublished'],
];
$item = $map[$s] ?? ['cls' => 'badge-secondary', 'label' => ucfirst($status)];
$sizeClass = $size === 'sm' ? ' badge-sm' : '';
@endphp
<span class="badge {{ $item['cls'] }}{{ $sizeClass }}">{{ $item['label'] }}</span>
