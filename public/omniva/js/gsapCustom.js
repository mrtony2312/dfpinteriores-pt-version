(function ($) {
    ("use strict");
    // DOM Ready

    gsap.registerPlugin(ScrollTrigger);

    var changetext = function () {
        if ($(".text-color-change").length) {
            $(".text-color-change").each(function () {
                const $el = $(this)[0];

                $el.wordSplit?.revert();
                $el.charSplit?.revert();

                $el.wordSplit = new SplitText($el, {
                    type: "words",
                    wordsClass: "word-wrapper",
                });
                $el.charSplit = new SplitText($el.wordSplit.words, {
                    type: "chars",
                    charsClass: "char-wrapper",
                });

                gsap.set($el.charSplit.chars, { color: "#D4D4D4" });

                gsap.to($el.charSplit.chars, {
                    color: "#000",
                    stagger: { each: 0.03, from: "start" },
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: $el,
                        start: "top 90%",
                        end: "bottom 35%",
                        scrub: 1.5,
                        toggleActions: "play none none reverse",
                    },
                });
            });
        }
        if ($(".text-color-change-2").length) {
            $(".text-color-change-2").each(function () {
                const $el = $(this)[0];

                $el.wordSplit?.revert();
                $el.charSplit?.revert();

                $el.wordSplit = new SplitText($el, {
                    type: "words",
                    wordsClass: "word-wrapper",
                });
                $el.charSplit = new SplitText($el.wordSplit.words, {
                    type: "chars",
                    charsClass: "char-wrapper",
                });

                gsap.set($el.charSplit.chars, { color: "#707070" });

                gsap.to($el.charSplit.chars, {
                    color: "#111111",
                    stagger: { each: 0.03, from: "start" },
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: $el,
                        start: "top 90%",
                        end: "bottom 35%",
                        scrub: 1.5,
                        toggleActions: "play none none reverse",
                    },
                });
            });
        }
        if ($(".text-color-change-3").length) {
            $(".text-color-change-3").each(function () {
                const $el = $(this)[0];

                $el.wordSplit?.revert();
                $el.charSplit?.revert();

                $el.wordSplit = new SplitText($el, {
                    type: "words",
                    wordsClass: "word-wrapper",
                });
                $el.charSplit = new SplitText($el.wordSplit.words, {
                    type: "chars",
                    charsClass: "char-wrapper",
                });

                gsap.set($el.charSplit.chars, { color: "#BBBBBB" });

                gsap.to($el.charSplit.chars, {
                    color: "#1A1A1A",
                    stagger: { each: 0.03, from: "start" },
                    ease: "power2.out",
                    scrollTrigger: {
                        trigger: $el,
                        start: "top 90%",
                        end: "bottom 35%",
                        scrub: 1.5,
                        toggleActions: "play none none reverse",
                    },
                });
            });
        }
    };
    
    var hoverScaleText = () => {
        if (!$(".text-scale").length > 0) return;
        const headings = document.querySelectorAll(".text-scale");
        headings.forEach((heading) => {
            const textNodes = [];

            heading.childNodes.forEach((node) => {
                if (node.nodeType === Node.TEXT_NODE) {
                    node.textContent
                        .split(" ")
                        .forEach((word, index, array) => {
                            const wordSpan = document.createElement("span");
                            wordSpan.classList.add("tp-word-span");
                            word.split("").forEach((letter) => {
                                const letterSpan =
                                    document.createElement("span");
                                letterSpan.classList.add("tf-letter");
                                letterSpan.textContent = letter;
                                wordSpan.appendChild(letterSpan);
                            });
                            textNodes.push(wordSpan);
                            if (index < array.length - 1) {
                                textNodes.push(document.createTextNode(" "));
                            }
                        });
                } else if (node.nodeType === Node.ELEMENT_NODE) {
                    textNodes.push(node.cloneNode(true));
                }
            });

            heading.innerHTML = "";
            textNodes.forEach((node) => heading.appendChild(node));

            const letters = heading.querySelectorAll(".tf-letter");
            letters.forEach((letter) => {
                $(letter).on("mouseenter", () => {
                    gsap.to(letter, {
                        scaleY: 1.3,
                        y: "-14%",
                        duration: 0.2,
                        ease: "sine",
                    });
                });

                $(letter).on("mouseleave", () => {
                    gsap.to(letter, {
                        scaleY: 1,
                        y: "0%",
                        duration: 0.2,
                        ease: "sine",
                    });
                });
            });
        });
    };


    document.addEventListener("DOMContentLoaded", function () {
        hoverScaleText();
    });
})(jQuery);
