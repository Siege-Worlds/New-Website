<div class="section quote is-medium bg-medium" style="position:relative;overflow:hidden;">
    <div class="dark-bar-pattern"></div>
    <div style="position:absolute;top:0;left:0;right:0;bottom:0;display:flex;align-items:center;overflow:hidden;z-index:0;pointer-events:none;">
        <div style="display:flex;animation:filmstrip-scroll 30s linear infinite;opacity:0.3;">
            <img src="img/filmstrip_screenshots_1.webp" style="min-width:100vw;height:auto;" alt="" /><img src="img/filmstrip_screenshots_1.webp" style="min-width:100vw;height:auto;" alt="" />
        </div>
    </div>
    <div class="container" style="position:relative;z-index:1;">
        <div class="quote-slide active" id="quote-0">
            <div class="quote-with-headshot">
                <img src="img/headshot_geoff_600px.webp" alt="Geoff McCabe" class="quote-headshot" />
                <div class="quote-text">
                    <h1>"We aimed to craft a game that transforms players into cinematic heroes; envisioning a battle experience against massive hordes, like the epic scenes found in Lord of the Rings."</h1>
                    <a href="https://www.gamesinteractive.co.uk/" target="_blank" class="company">Geoff McCabe (Co-founder Games Interactive)</a>
                </div>
            </div>
        </div>
        <div class="quote-slide" id="quote-1">
            <div class="quote-with-headshot">
                <img src="img/headshot_jake_600px.webp" alt="Jake O'Connor" class="quote-headshot" />
                <div class="quote-text">
                    <h1>"Fun is not accidental, it is designed, and Siege Worlds was built from those principles from day one. Siege Worlds is me finally building the kind of game I always wanted to play."</h1>
                    <a href="https://www.gamesinteractive.co.uk/" target="_blank" class="company">Jake O'Connor (Co-founder Games Interactive)</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            var currentQuote = 0;
            var quotes = document.querySelectorAll('.quote-slide');
            setInterval(function() {
                quotes[currentQuote].classList.remove('active');
                currentQuote = (currentQuote + 1) % quotes.length;
                quotes[currentQuote].classList.add('active');
            }, 5000);
        })();
    </script>
    <div class="dark-bar-pattern bottom flip"></div>
</div>
<div class="section trailer-video bg-dark">
    <div class="container">
        <div class="trailer-wrapper">
            <iframe src="https://www.youtube.com/embed/C9AuKJGIRns?autoplay=1&mute=1&loop=1&playlist=C9AuKJGIRns&controls=0&showinfo=0&rel=0&modestbranding=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
    </div>
</div>
