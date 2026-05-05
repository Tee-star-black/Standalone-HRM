<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Payslip {{ $payslip->period }}
            </h2>

            <a href="{{ route('my-payslips.index') }}" class="px-4 py-2 bg-gray-200 rounded">
                Back
            </a>

            <a href="{{ route('my-payslips.download', $payslip) }}" class="px-4 py-2 bg-green-600 text-white rounded">
                Download PDF
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-8 rounded shadow">
                <div class="border-b pb-4 mb-6">
                    <h1 class="text-2xl font-bold">Payslip</h1>
                    <p class="text-gray-500">Period: {{ $payslip->period }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <p><strong>Employee:</strong> {{ $payslip->employee->full_name }}</p>
                    <p><strong>Employee No:</strong> {{ $payslip->employee->employee_number }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($payslip->status) }}</p>
                </div>

                <table class="w-full text-left mb-6">
                    <tbody>
                        <tr>
                            <td class="border-b p-2">Basic Salary</td>
                            <td class="border-b p-2 text-right">{{ number_format($payslip->basic_salary, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="border-b p-2">Allowances</td>
                            <td class="border-b p-2 text-right">{{ number_format($payslip->allowances, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="border-b p-2">Deductions</td>
                            <td class="border-b p-2 text-right">{{ number_format($payslip->deductions, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="border-b p-2">Tax</td>
                            <td class="border-b p-2 text-right">{{ number_format($payslip->tax, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold text-lg">Net Pay</td>
                            <td class="p-2 text-right font-bold text-lg">{{ number_format($payslip->net_pay, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                @if ($payslip->notes)
                    <div class="bg-gray-50 p-4 rounded">
                        <strong>Notes:</strong>
                        <p>{{ $payslip->notes }}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>