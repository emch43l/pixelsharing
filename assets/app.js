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
});

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
    let buttons = $("button[data-like-button]");
    [...buttons].forEach(btn => {
        if(btn != null) {
            $(btn).on('click', e => {

                $(btn).attr('disabled','disabled');

                let element = $('span[data-image-votes-identifier='+$(btn).data('like-button')+']');
                let voteElement = $(btn).data('vote-type');
                let voteType = voteElement == true ? true : false;

                let request = $.ajax({
                    method: 'POST',
                    url :'/vote/add',
                    data: {
                        image : $(btn).data('like-button'),
                        type: voteType
                    },
                    headers: {
                        'Accept': 'application/json'
                    },
                }).done(e => {

                   let votes = parseInt($(element).data('image-votes'));

                   voteType === true ?
                       votes++ : votes--;

                   element.text(votes);

                   SnackBar.show({
                       text: 'Added your vote !',
                       actionText: 'Ok'
                   });

                   $(btn).data('vote-type',!voteType);
                   $(element).data('image-votes',votes);

                }).fail(e => {
                    if(e.status == 401) {
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
            });
        }
    });
}