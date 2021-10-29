import AOS from 'aos';
import 'aos/dist/aos.css';
import feather from 'feather-icons';
import { jarallax, jarallaxVideo } from 'jarallax';
import { locomo } from './loco.js'
import Splide from '@splidejs/splide';
import '@splidejs/splide/dist/css/themes/splide-default.min.css';

export default {
    init() {
        const $ = (el, parent) => (parent || document).querySelector(el);
        const $$ = (el, parent) => (parent || document).querySelectorAll(el);

        AOS.init()
        feather.replace()
        jarallaxVideo()
        locomo()

        /** Bulma 
         * @docs https://bulmajs.tomerbe.co.uk/docs/0.11/1-getting-started/1-introduction/
         */

        /**Helpers */

        const localizeNumbers = (text) => {
            const options = { style: 'currency', currency: 'USD' };
            const NF = new Intl.NumberFormat('en-US', options);

            return NT.format(text)
        }

        const numbers = document.querySelectorAll('.number')
        numbers.forEach(number => {
            console.log(number)
            number.text ? number.text = localizeNumbers(number.text) : number.addEventListener('change', () => {
                                                                            number.value = localizeNumbers(number.value)
                                                                        })
        })


        /**Parallax */

        jarallax(document.querySelectorAll('.is-parallax-contain'), {
            speed: 0.9,
            imgSize: 'cover',
            imgPosition: '25% 50%',
        })

        jarallax(document.querySelectorAll('.is-parallax-cover'), {
            speed: 0.4,
            imgSize: 'cover',
            imgPosition: '25% 50%',
        })

        document.querySelectorAll('.is-parallax-video').forEach(element => {
            jarallax(element, {
                speed: 0.4,
                videoSrc: `mp4:${element.dataset.url}`
            });
        });
        window.onmousemove = (e) => {
            $('.pointer-sombra').style.transform = `translate3d(${e.clientX}px, ${e.clientY}px, 0)`
        }

        document.querySelector('.open-menu')?.addEventListener('click', () => {
            $('.open-menu').classList.toggle('active')
            $('.menu-desplegable').classList.toggle('active')
            $('nav').classList.toggle('open')
        })

        $$('.menu-item a').forEach(el => {
            el.dataset.text = el.textContent;
        })

        if ($('.splide')) {
            new Splide('.splide', {
                type: 'slide',
                perPage: 6,
                perMove: 4,
                pagination: false,
                speed: 500,
                breakpoints: {
                    1150: {
                        perPage: 5,
                    },

                    800: {
                        perPage: 3,
                    },

                    600: {
                        perPage: 2,
                    }
                }
            }).mount();
        }

    },
    finalize() {
        // JavaScript to be fired on all pages, after page specific JS is fired
    },
};