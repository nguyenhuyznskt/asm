{{-- resources/views/admin/activity_logs/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Activity Logs')
@section('page_title', 'Nhật ký hoạt động')
@section('page_subtitle', 'Theo dõi hành động của user trong hệ thống')

@section('content')
<div class="bg-slate-900/80 border border-slate-800 rounded-xl p-4">
    <table class="w-full text-sm">
        <thead class="text-xs uppercase text-slate-400 border-b border-slate-700">
        <tr>
            <th class="text-left py-2">Thời gian</th>
            <th class="text-left py-2">User</th>
            <th class="text-left py-2">Sự kiện</th>
            <th class="text-left py-2">Mô tả</th>
            <th class="text-left py-2">IP</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
        @forelse($logs as $log)
            <tr>
                <td class="py-2 text-slate-300">
                    {{ $log->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="py-2">
                    {{ $log->user->name ?? 'Guest' }}
                </td>
                <td class="py-2 text-emerald-400">
                    {{ $log->event }}
                </td>
                <td class="py-2 text-slate-300">
                    {{ $log->description }}
                </td>
                <td class="py-2 text-slate-400">
                    {{ $log->ip }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="py-4 text-center text-slate-500">
                    Chưa có log nào.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
