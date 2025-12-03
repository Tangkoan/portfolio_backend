@extends('admin.dashboard')

@section('content')
<div class="w-full h-full px-1 py-1">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text-color flex items-center gap-2">
                <i class="ri-git-merge-line text-primary"></i> Assignment Rules
            </h1>
           
        </div>
    </div>

    <div class="bg-card-bg rounded-xl shadow-custom border border-border-color overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-page-bg/50 border-b border-border-color text-text-color text-sm uppercase tracking-wider">
                    <th class="px-6 py-4 font-bold">Role Name</th>
                    <th class="px-6 py-4 font-bold text-center">Assignable Permissions</th>
                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-color">
                @forelse($roles as $role)
                <tr class="hover:bg-page-bg/30 transition-colors">
                    
                    <td class="px-6 py-4">
                        <span class="font-bold text-text-color text-lg">{{ $role->name }}</span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        @if($role->assignable_permissions_count > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Can assign {{ $role->assignable_permissions_count }} permissions
                            </span>
                        @else
                            <span class="text-xs text-secondary italic">Cannot assign any permissions</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.rules.edit', $role->id) }}" 
                           class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 px-4 py-2 rounded-lg font-medium transition-all shadow-sm">
                            <i class="ri-settings-4-line"></i> Configure
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-secondary">No roles found (except Super Admin).</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection