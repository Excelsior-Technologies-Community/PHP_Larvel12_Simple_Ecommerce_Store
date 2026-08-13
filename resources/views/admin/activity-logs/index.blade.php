@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div class="glass-card p-4">
    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control glass-form-control" placeholder="Search logs...">
        </div>
        <div class="col-md-3">
            <select name="action" class="form-select glass-form-control">
                <option value="">All Actions</option>
                @foreach($actions ?? [] as $action)
                    <option value="{{ $action }}" {{ ($action ?? '') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary-glass w-100">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td><span class="badge badge-glass">{{ $log->action }}</span></td>
                        <td>{{ $log->description ?? '-' }}</td>
                        <td>{{ $log->ip_address ?? '-' }}</td>
                        <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No logs found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
