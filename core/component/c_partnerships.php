<div class="section partnerships bg-dark">
    <div class="container">
        <h1 class="partnerships-title">Sponsors</h1>

        <div class="partnerships-carousel">
            <button class="carousel-btn carousel-btn-left" onclick="partnerCarousel(-1)">&#10094;</button>

            <div class="carousel-track">
                <!-- Partner 1 -->
                <div class="partner-slide active">
                    <div class="partner-content">
                        <div class="partner-left">
                            <div class="partner-logo">
                                <img src="img/placeholder-logo.png" onerror="this.style.display='none'" alt="Partner Logo" />
                                <span class="placeholder-text">Partner Logo</span>
                            </div>
                            <div class="partner-description">
                                <p>Partner description goes here. Add details about the partnership, collaboration, and shared goals.</p>
                            </div>
                        </div>
                        <div class="partner-media">
                            <div class="placeholder-media">Partner Image / Video</div>
                        </div>
                    </div>
                </div>

                <!-- Partner 2 -->
                <div class="partner-slide">
                    <div class="partner-content">
                        <div class="partner-left">
                            <div class="partner-logo">
                                <img src="img/placeholder-logo.png" onerror="this.style.display='none'" alt="Partner Logo" />
                                <span class="placeholder-text">Partner Logo</span>
                            </div>
                            <div class="partner-description">
                                <p>Partner description goes here. Add details about the partnership, collaboration, and shared goals.</p>
                            </div>
                        </div>
                        <div class="partner-media">
                            <div class="placeholder-media">Partner Image / Video</div>
                        </div>
                    </div>
                </div>

                <!-- Partner 3 -->
                <div class="partner-slide">
                    <div class="partner-content">
                        <div class="partner-left">
                            <div class="partner-logo">
                                <img src="img/placeholder-logo.png" onerror="this.style.display='none'" alt="Partner Logo" />
                                <span class="placeholder-text">Partner Logo</span>
                            </div>
                            <div class="partner-description">
                                <p>Partner description goes here. Add details about the partnership, collaboration, and shared goals.</p>
                            </div>
                        </div>
                        <div class="partner-media">
                            <div class="placeholder-media">Partner Image / Video</div>
                        </div>
                    </div>
                </div>
            </div>

            <button class="carousel-btn carousel-btn-right" onclick="partnerCarousel(1)">&#10095;</button>
        </div>

        <div class="carousel-dots">
            <span class="dot active" onclick="goToSlide(0)"></span>
            <span class="dot" onclick="goToSlide(1)"></span>
            <span class="dot" onclick="goToSlide(2)"></span>
        </div>

        <div class="partner-cta">
            <h2>Partner With Us</h2>
            <a href="mailto:geoff@lightningworks.io">geoff@lightningworks.io</a>
        </div>
    </div>
</div>

<script>
    var currentSlide = 0;
    var totalSlides = 3;
    var partnerInterval;

    function showSlide(index) {
        var slides = document.querySelectorAll('.partner-slide');
        var dots = document.querySelectorAll('.carousel-dots .dot');
        currentSlide = ((index % totalSlides) + totalSlides) % totalSlides;
        for (var i = 0; i < slides.length; i++) {
            slides[i].classList.remove('active');
            dots[i].classList.remove('active');
        }
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }

    function partnerCarousel(direction) {
        showSlide(currentSlide + direction);
        resetAutoScroll();
    }

    function goToSlide(index) {
        showSlide(index);
        resetAutoScroll();
    }

    function resetAutoScroll() {
        clearInterval(partnerInterval);
        partnerInterval = setInterval(function() { showSlide(currentSlide + 1); }, 7000);
    }

    partnerInterval = setInterval(function() { showSlide(currentSlide + 1); }, 7000);
</script>
