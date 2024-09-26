<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg mb-4 flex gap-4">
                <div class="p-6 bg-white dark:bg-gray-800  text-gray-900 dark:text-gray-100">
                    Companies : {{ $companyCount }}
                </div>
                <div class="p-6 bg-white dark:bg-gray-800  text-gray-900 dark:text-gray-100">
                    Employees : {{ $employeeCount }}
                </div>
            </div>
            <div class="relative overflow-x-auto">
                       
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead
                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Logo
                            </th>
                            <th scope="col" class="px-6 py-3">
                               Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                           
                            <th scope="col" class="px-6 py-3">
                                Website
                            </th>
                          
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentCompanies as $company)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                <img src="{{ $company->getLogoPath() }}" alt="{{ $company->name }}" class="w-12 h-12">
                            </td>
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $company->name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $company->email }}
                            </td>
                            
                            <td class="px-6 py-4">
                                {{ $company->website }}
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
              

            </div>
        </div>
       
    </div>
</x-app-layout>
