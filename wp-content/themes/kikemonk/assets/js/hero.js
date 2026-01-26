// use a script tag or an external JS file
const Hero = {
  init() {
    // gsap.registerPlugin(Flip,ScrollTrigger,ScrollToPlugin,TextPlugin)

    document.querySelectorAll('.hero-block').forEach(block => {
      var headerTL = gsap.timeline(),
        title = block.querySelector('.hero-title'),
        content = block.querySelector('.h-content'),
        cta = block.querySelector('.hero-cta'),
        mask = block.querySelector('.circle-mask'),
        image = block.querySelector('.hero-image');

      // GSAP Sets
      gsap.set(block, { autoAlpha: 0 });
      gsap.set(title, { y: -40, autoAlpha: 0 });
      gsap.set(content, { autoAlpha: 0 });
      gsap.set(cta, { y: 40, autoAlpha: 0 });
      gsap.set(mask, { autoAlpha: 0 });
      gsap.set(image, { autoAlpha: 0, scale: 0 });

      headerTL
        .to(block, { autoAlpha: 1 })
        .to(mask, { delay: .5, autoAlpha: 1 })
        .to(title, { y: 0, autoAlpha: 1 })
        .to(content, { autoAlpha: 1 })
        .to(cta, { y: 0, autoAlpha: 1 })
        .to(image, { scale: 1, autoAlpha: 1 });
    });
  }
};

export default Hero;