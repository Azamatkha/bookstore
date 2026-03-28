<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl bg-sky-900 px-5 py-3 text-sm font-semibold text-white transition duration-300 hover:bg-sky-800 hover:shadow-[0_18px_30px_-18px_rgba(3,105,161,0.55)] focus:outline-none focus:ring-2 focus:ring-blue-500/25 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
