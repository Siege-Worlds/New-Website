<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<div class="section partnerships bg-dark">
    <div class="container">
        <h1 class="partnerships-title">Sponsors</h1>
        <p class="partnerships-subtitle">Build community for your brand and token with a custom game wave!</p>

        <div class="swiper" id="sponsor-swiper">
            <div class="swiper-wrapper">

                <div class="swiper-slide">
                    <div class="sponsor-card">
                        <div class="sponsor-info">
                            <div class="sponsor-logo"><img src="img/sponsorlogo_1.webp" alt="Alien Worlds Galactic Hubs" /></div>
                            <div class="sponsor-text">
                                <h3>ALIEN WORLDS' GALACTIC HUBS</h3>
                                <p>Thanks to a grant from GHubs, we were able to build Bleakrock Island, known throughout the Starblind universe as one of the most dangerous places in the six worlds.</p>
                            </div>
                        </div>
                        <div class="sponsor-image"><img src="img/sponsor_image_1.webp" alt="Alien Worlds Galactic Hubs" /></div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="sponsor-card">
                        <div class="sponsor-info">
                            <div class="sponsor-logo"><img src="img/sponsorlogo_2.webp" alt="Harold Health" /></div>
                            <div class="sponsor-text">
                                <h3>HAROLD HEALTH</h3>
                                <p>A grant from Harold Health allowed us to build Harold's Island. Here, Harold battles his inner demons on the neighborhood streets of his imagination, earning $HAROLD meme coin.</p>
                            </div>
                        </div>
                        <div class="sponsor-image"><img src="img/sponsor_image_2.webp" alt="Harold Health" /></div>
                    </div>
                </div>

                <div class="swiper-slide">
                    <div class="sponsor-card">
                        <div class="sponsor-info">
                            <div class="sponsor-logo"><img src="img/sponsorlogo_3.webp" alt="Nero Chain" /></div>
                            <div class="sponsor-text">
                                <h3>NERO CHAIN</h3>
                                <p>An ultra-fast EVM chain, known for its best-of-class control of GAS FEES, Nero's island will feature Roman architecture and a Colosseum for epic boss and PVP battles.</p>
                            </div>
                        </div>
                        <div class="sponsor-image"><img src="img/sponsor_image_3.webp" alt="Nero Chain" /></div>
                    </div>
                </div>

            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="partner-cta">
            <h2>Partner With Us</h2>
            <a href="mailto:geoff@lightningworks.io">geoff@lightningworks.io</a>
        </div>
    </div>
</div>

<script>
    new Swiper('#sponsor-swiper', {
        loop: true,
        autoplay: { delay: 7000, disableOnInteraction: false },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        pagination: { el: '.swiper-pagination', clickable: true },
    });
</script>
