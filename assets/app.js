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

import SnackBar from 'node-snackbar';

import 'node-snackbar/dist/snackbar.min.css';

$(window).on('load', (data) => {
    likeButton();
    homeForm();
    mobileMenu();
});

function mobileMenu()
{
    let isOpen = $('body[data-menu-open]').data('menu-open');
    let menu = $('#mobie-menu');
    let openButton = $('#menu-open-button');
    let closeButton = $('#menu-close-button');
    if(openButton == null || openButton == undefined)
        return;

    openButton.on('click',e => {
        $(menu).addClass('menu-open');
    });

    closeButton.on('click',e => {
        $(menu).removeClass('menu-open');
    })

}

function homeForm()
{
    let buttons = $('button[data-home-page-number]');
    let input = $('#page-number-input');
    let catInput = $('#image-category');
    let form = $('#home-form');
    let categories = $('input[data-image-category]');

    if(input === null || input === undefined || form === null || form === undefined)
        return;

    [...buttons].forEach(btn => {
        $(btn).on('click', e => {
            input.attr('value',parseInt($(btn).data('home-page-number')));
            form.submit();
        });
    });

    [...categories].forEach(btn => {
        $(btn).on('click', e => {
            e.preventDefault();
            catInput.attr('value',$(btn).data('image-category'));
            form.submit();
        })
    });
}

function likeButton()
{
    let likeButton = $('#pixel-like');
    let dislikeButton = $('#pixel-dislike');
    let likeNumber = $('#pixel-like-number-span');

    if(likeButton === null || likeButton === undefined || dislikeButton === null || dislikeButton === undefined)
        return;

    $(likeButton).on('click', (e) => {
        sendRequest(true,$(likeButton).data('image-id'),$(likeButton),() => {

            let likes = parseInt($(likeNumber).text());
            if($(likeNumber).data('user-liked') === false)
            {
                $(likeNumber).text((likes + 1));
                $(likeNumber).data('user-liked',true);
            }

            SnackBar.show({
                text: 'Added your like vote !',
            });

        });
    });

    $(dislikeButton).on('click', (e) => {
        sendRequest(false,$(dislikeButton).data('image-id'),$(dislikeButton),() => {

            let likes = parseInt($(likeNumber).text());
            if($(likeNumber).data('user-liked') === true)
            {
                $(likeNumber).text((likes - 1));
                $(likeNumber).data('user-liked',false);
            }

            SnackBar.show({
                text: 'Added your dislike vote !',
            });
        });
    });

}

function sendRequest(voteType, imageId, btn, done)
{
    $(btn).attr('disabled','disabled');

    let request = $.ajax({
        method: 'POST',
        url :'/vote/add',
        data: {
            image : imageId,
            type: voteType
        },
        // IMPORTANT
        headers: {
            'Accept': 'application/json'
        },
    }).done(e => done()).fail(e => {
        if(e?.status === 401) {
            SnackBar.show({
                text: 'Please log in to use this action !',
                actionText : 'Login',
                onActionClick: function(element) {
                    $(window).attr('location','/login');
                }
            });
        } else {
            SnackBar.show({
                text: 'An error occured', showAction: false
            });
        }
    }).always(e => {
        $(btn).removeAttr('disabled');
    });
}