@extends('layouts.web.master')

@section('content')
<main class="overflow-hidden bg-[#161616]">
    <!-- Inner Banner -->
    <section class="relative py-28 flex items-center bg-[#000000] overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/hero-bg-image.png') }}" alt="Merch Background" class="w-full h-full object-cover opacity-40">
        </div>
        <div class="container mx-auto px-6 lg:px-16 relative z-10 text-center">
            <h1 class="text-[40px] md:text-[64px] font-bold text-white mb-4" data-aos="fade-down">
                Official <span class="text-(--primary-color)">Merch</span>
            </h1>
            <p class="text-(--text-white) text-[18px] md:text-[20px] max-w-2xl mx-auto font-normal" data-aos="fade-up" data-aos-delay="100">
                Premium apparel and accessories designed for the modern DJ and MC.
            </p>
        </div>
    </section>

    <!-- Simple Product Grid -->
    <section class="py-20">
        <div class="container mx-auto px-6 lg:px-16">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
                
                <!-- Product 1 -->
                <div class="product-card" data-aos="fade-up">
                    <div class="product-img-wrapper">
                        <img src="{{ asset('images/shop-img-001.png') }}" alt="DJ Nova T-Shirt" class="product-img ">
                    </div>
                    <span class="product-category">Sweatshirts</span>
                    <h3 class="product-name">DJ Nova T-Shirt</h3>
                    <p class="product-price">$29.99</p>
                    <button class="mt-4 w-full py-3 bg-[#1F1F1F] text-white border border-[#282828] hover:bg-(--primary-color) hover:text-black hover:border-(--primary-color) transition-all font-bold uppercase text-xs tracking-widest">Add to Cart</button>
                </div>

                <!-- Product 2 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-img-wrapper">
                        <img src="{{ asset('images/shop-img-002.png') }}" alt="DJ Blocknote Hat" class="product-img">
                    </div>
                    <span class="product-category">Accessories</span>
                    <h3 class="product-name">DJ Blocknote Hat</h3>
                    <p class="product-price">$19.99</p>
                    <button class="mt-4 w-full py-3 bg-[#1F1F1F] text-white border border-[#282828] hover:bg-(--primary-color) hover:text-black hover:border-(--primary-color) transition-all font-bold uppercase text-xs tracking-widest">Add to Cart</button>
                </div>

                <!-- Product 3 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="product-img-wrapper">
                        <img src="{{ asset('images/shop-img-003.png') }}" alt="Event Essentials Tote Bag" class="product-img">
                    </div>
                    <span class="product-category">Bags</span>
                    <h3 class="product-name">Event Essentials Tote Bag</h3>
                    <p class="product-price">$15.99</p>
                    <button class="mt-4 w-full py-3 bg-[#1F1F1F] text-white border border-[#282828] hover:bg-(--primary-color) hover:text-black hover:border-(--primary-color) transition-all font-bold uppercase text-xs tracking-widest">Add to Cart</button>
                </div>

                <!-- Product 4 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="product-img-wrapper">
                        <img src="{{ asset('images/shop-img-004.png') }}" alt="Soundwave Hoodie" class="product-img">
                    </div>
                    <span class="product-category">T-shirts</span>
                    <h3 class="product-name">Soundwave Hoodie</h3>
                    <p class="product-price">$39.99</p>
                    <button class="mt-4 w-full py-3 bg-[#1F1F1F] text-white border border-[#282828] hover:bg-(--primary-color) hover:text-black hover:border-(--primary-color) transition-all font-bold uppercase text-xs tracking-widest">Add to Cart</button>
                </div>

                <!-- Product 5 (Re-using some images for variety) -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-img-wrapper">
                        <img src="{{ asset('images/shop-img-001.png') }}" alt="Rhythm Crewneck" class="product-img">
                    </div>
                    <span class="product-category">Sweatshirts</span>
                    <h3 class="product-name">Rhythm Crewneck</h3>
                    <p class="product-price">$34.99</p>
                    <button class="mt-4 w-full py-3 bg-[#1F1F1F] text-white border border-[#282828] hover:bg-(--primary-color) hover:text-black hover:border-(--primary-color) transition-all font-bold uppercase text-xs tracking-widest">Add to Cart</button>
                </div>

                <!-- Product 6 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="product-img-wrapper">
                        <img src="{{ asset('images/shop-img-002.png') }}" alt="Vinyl Snapback" class="product-img">
                    </div>
                    <span class="product-category">Accessories</span>
                    <h3 class="product-name">Vinyl Snapback</h3>
                    <p class="product-price">$24.99</p>
                    <button class="mt-4 w-full py-3 bg-[#1F1F1F] text-white border border-[#282828] hover:bg-(--primary-color) hover:text-black hover:border-(--primary-color) transition-all font-bold uppercase text-xs tracking-widest">Add to Cart</button>
                </div>

                <!-- Product 7 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="product-img-wrapper">
                        <img src="{{ asset('images/shop-img-003.png') }}" alt="DJ Master Duffel" class="product-img">
                    </div>
                    <span class="product-category">Bags</span>
                    <h3 class="product-name">DJ Master Duffel</h3>
                    <p class="product-price">$49.99</p>
                    <button class="mt-4 w-full py-3 bg-[#1F1F1F] text-white border border-[#282828] hover:bg-(--primary-color) hover:text-black hover:border-(--primary-color) transition-all font-bold uppercase text-xs tracking-widest">Add to Cart</button>
                </div>

                <!-- Product 8 -->
                <div class="product-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="product-img-wrapper">
                        <img src="{{ asset('images/shop-img-004.png') }}" alt="Classic Logo Tee" class="product-img">
                    </div>
                    <span class="product-category">T-shirts</span>
                    <h3 class="product-name">Classic Logo Tee</h3>
                    <p class="product-price">$24.99</p>
                    <button class="mt-4 w-full py-3 bg-[#1F1F1F] text-white border border-[#282828] hover:bg-(--primary-color) hover:text-black hover:border-(--primary-color) transition-all font-bold uppercase text-xs tracking-widest">Add to Cart</button>
                </div>

            </div>
        </div>
    </section>

    <!-- Collection Note -->
    <section class="py-20 bg-[#1F1F1F] text-center border-t border-[#282828]">
        <div class="container mx-auto px-6 lg:px-16" data-aos="zoom-in">
            <h2 class="text-[30px] md:text-[40px] font-bold text-white mb-6">Free Shipping on Orders Over $75</h2>
            <p class="text-(--text-white) font-normal text-lg mb-8">Wear the rhythm. Represent the global community of verified DJs and MCs.</p>
            <a href="#" class="btn primary-button px-10! py-4!">Contact Support</a>
        </div>
    </section>
</main>
@endsection
