@props(['message' => null, 'type' => 'success'])

<div x-data="{ show: false, message: '{{ $message }}' }" 
     x-show="show" 
     x-init="if(message) { show = true; setTimeout(() => show = false, 3000) }"
     @toast.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="opacity-0 translate-x-full"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-full"
     class="fixed top-5 right-5 z-50 flex items-center p-4 mb-4 w-full max-w-xs text-gray-500 bg-white rounded-xl shadow-xl border border-gray-100 dark:text-gray-400 dark:bg-gray-800" 
     style="display: none;">
    
    <div class="inline-flex flex-shrink-0 justify-center items-center w-8 h-8 rounded-lg"
         :class="{
             'text-emerald-500 bg-emerald-100': '{{ $type }}' === 'success',
             'text-red-500 bg-red-100': '{{ $type }}' === 'error',
             'text-amber-500 bg-amber-100': '{{ $type }}' === 'warning'
         }">
        
        <!-- Success Icon -->
        <svg x-show="'{{ $type }}' === 'success'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
        
        <!-- Error Icon -->
        <svg x-show="'{{ $type }}' === 'error'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        
    </div>
    <div class="ml-3 text-sm font-semibold text-gray-900" x-text="message"></div>
    <button @click="show = false" type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8">
        <span class="sr-only">Close</span>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
    </button>
</div>