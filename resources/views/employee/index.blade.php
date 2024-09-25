<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Employees') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between">
                        <form action="" method="get">
                            <div class="pb-3">
                                <input type="search" name="search" id="table-search" class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for employees" value="{{ request()->search }}">
                            </div>
                        </form>
                        <div class="flex items-center justify-end">
                            <a href="{{ route('employees.create') }}">
                                <x-primary-button class="ms-3">
                                    {{ __('Create New Employee') }}
                                </x-primary-button>
                            </a>
                        </div>
                    </div>
                   
                    <div class="relative overflow-x-auto">
                       
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Profile
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                       Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Email
                                    </th>
                                   
                                    <th scope="col" class="px-6 py-3">
                                        Phone
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Company Name
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">
                                        <img src="{{ $employee->getProfilePath() }}" alt="{{ $employee->name }}" class="w-12 h-12">
                                    </td>
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $employee->name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $employee->email }}
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        {{ $employee->phone }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $employee->company?->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('employees.edit',$employee->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                        <form action="{{ route('employees.destroy',$employee->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                 class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr class="text-center">
                                    <td colspan="4">
                                        Nothing Here.
                                    </td>
                                </tr>
                               
                                @endforelse
                                
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                        <div class="pt-3">
                            {{ $employees->links() }}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
