const LogoGrid = {
  init() {
    // gsap.registerPlugin(Flip, ScrollTrigger, ScrollToPlugin, TextPlugin);

    document.querySelectorAll('.logo-grid-block').forEach(block => {
      var gridTL = gsap.timeline();
      var logos = block.querySelectorAll('.grid-item'),
        title = block.querySelector('.grid-title'),
        cta = block.querySelector('.grid-cta');

      // GSAP Sets
      gsap.set(logos, { y: -40, autoAlpha: 0 });
      gsap.set(title, { y: 40, autoAlpha: 0 });
      gsap.set(cta, { autoAlpha: 0 });

      gridTL
        .to(title, { y: 0, autoAlpha: 1 })
        .to(logos, { y: 0, autoAlpha: 1, stagger: 0.2 })
        .to(cta, { autoAlpha: 1 });

      ScrollTrigger.create({
        trigger: block,
        start: 'top center',
        end: 'bottom',
        animation: gridTL
      });
    });
  }
};

export default LogoGrid;
