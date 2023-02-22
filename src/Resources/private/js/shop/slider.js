import { slick } from 'slick-carousel'

export default () => {
    $('.carousel-single').slick({
        infinite: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: $('.carousel-left'),
        nextArrow: $('.carousel-right'),
        appendArrows: false
    });
}
