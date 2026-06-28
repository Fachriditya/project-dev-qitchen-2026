@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 px-4 py-2.5 focus:border-orange-500 focus:ring-orange-500 rounded-xl shadow-sm transition duration-150 ease-in-out w-full']) }}>