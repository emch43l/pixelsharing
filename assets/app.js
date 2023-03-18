/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

$(window).on('load', (data) => {
    likeButton();
});

function likeButton()
{
    let buttons = $('button').data('like-button');
    [...buttons].forEach(btn => {
        if(btn != null) {
            btn.on('click',e => {
                alert('działa');
            });
        }
    });
}