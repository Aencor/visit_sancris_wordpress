import gsap from "gsap";
import { SplitText } from "gsap/SplitText";
import Flip from "gsap/Flip";
import ScrollTrigger from "gsap/ScrollTrigger";
import ScrollToPlugin from "gsap/ScrollToPlugin";
import TextPlugin from "gsap/TextPlugin";
import { Timeline } from "gsap/gsap-core";
gsap.registerPlugin(Flip, ScrollTrigger, SplitText, ScrollToPlugin, TextPlugin);

export default {
  init() {


  },
};
