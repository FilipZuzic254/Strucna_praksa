<x-filament-widgets::widget>
    <x-filament::section>
        <link
        rel="stylesheet"
        href="{{ asset('css/swiper-bundle.min.css') }}"
        />

        <!-- Slider main container -->
        <div class="swiper" id="gallery-slider">

            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">

                @foreach($images as $image)
                    <div class="swiper-slide" style="display: flex; justify-content: center; align-items: center; height: 500px; ">
                        <img 
                            src="{{ Storage::url($image->image_path) }}" 
                            class="rounded-lg"
                            style="height: 400px;" 
                        >
                    </div>
                @endforeach

                <!-- <div class="swiper-slide">Slide 1</div> -->
            </div>

            <!-- If we need pagination -->
            <div class="swiper-pagination"></div>

            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>

        
        @assets
        <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
        @endassets
        
        @script
        <script>
            $nextTick(() => {
                console.log('Swiper element:', document.getElementById('gallery-slider'));
                console.log('Swiper slides:', document.querySelectorAll('#gallery-slider .swiper-slide').length);
    
                new Swiper('#gallery-slider', {
                    loop: true,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            });
        </script>
        @endscript

    
    </x-filament::section>
</x-filament-widgets::widget>
