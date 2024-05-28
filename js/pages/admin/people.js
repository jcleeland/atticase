$(function() {
    $('.data-heading').click(function() {
        var level = $(this).data('level');
        var nextElement = $(this).next();

        if (nextElement && nextElement.data('level') === level) {
            nextElement.toggleClass('hidden');
        }
    });

    // Add event listener to each input
    $('.row.mb-2.p-1 input[type="text"]').on('input', function() {
        // Show the "Save changes" button when a change is detected
        $(this).closest('.row.mb-2.p-1').find('.save-changes').show();
    });    


});

function deleteClient(id) {
    //First, check if the client has 
    if(confirm("Are you sure you want to delete this client from the Atticus database?")) {
        $.when(clientDelete(id)).done(function(output) {
            console.log(output);
            //window.location.href="?page=options&option=people"; 
            $('#client_row_'+id).remove();

        })
    }
};

function updateClient(id) {
    // Get the row for this client
    var row = $('#client_row_' + id);

    // Get the input values from this row
    var identifier = row.find('#id' + id).val();
    var surname = row.find('#surname' + id).val();
    var pref_name = row.find('#pref_name' + id).val();
    var started = new Date(row.find('#joined' + id).val()).getTime() / 1000;
    var primary_key = row.find('#primary_key' + id).val();
    var data = row.find('#data' + id).val();

    clientUpdate(identifier, surname, pref_name, started, primary_key, data)
    .done(function(response) {
        // Handle the response from the server
        console.log(response);

        // Hide the "Save changes" button
        row.find('.save-changes').hide();
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        // Handle any errors
        console.error('Error updating client: ', textStatus, ', Details: ', errorThrown);
        console.error('Response: ', jqXHR.responseText);
    });    
}

function togglePopup(popupId) {
    // Close all other popups
    var popups = document.querySelectorAll('.popup-container');
    for (var i = 0; i < popups.length; i++) {
        if (popups[i].id !== popupId) {
            popups[i].style.display = 'none';
        }
    }    
    var popup = document.getElementById(popupId);
    popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
    ensurePopupVisibility(popupId);
    adjustPopupHeight(popupId);
}

function ensurePopupVisibility(popupId) {
    var popup = document.getElementById(popupId);
    var parentDiv = document.querySelector('#listParent');

    if (popup && parentDiv) {
        var popupRect = popup.getBoundingClientRect();
        var parentDivRect = parentDiv.getBoundingClientRect();

        var popupBottomRelativeToParent = popupRect.bottom - parentDivRect.top;
        var parentDivScrollBottom = parentDiv.scrollTop + parentDiv.clientHeight;

        if (popupBottomRelativeToParent > parentDivScrollBottom) {
            parentDiv.scrollTop += (popupBottomRelativeToParent - parentDivScrollBottom);
        }
    }
}

function adjustPopupHeight(popupId) {
    var popup = document.getElementById(popupId);
    if (popup) {
        var contentHeight = popup.scrollHeight; // Gets the height of the content
        var maxHeight = 30 * window.innerHeight / 100; // 30vh converted to pixels

        if (contentHeight < maxHeight) {
            popup.style.height = contentHeight + 'px'; // Set height to content height if it's less than max
        } else {
            popup.style.height = maxHeight + 'px'; // Set height to maximum allowed height
        }
    }
}

function getSortParameters() {
    const params = new URLSearchParams(window.location.search);
    return {
        currentField: params.get('orderBy'),
        currentOrder: params.get('order')
    };
}

function sortResults(field) {
    let { currentField, currentOrder } = getSortParameters();

    let sortOrder;
    if (field === currentField) {
        sortOrder = (currentOrder === 'ASC') ? 'DESC' : 'ASC';
    } else {
        sortOrder = 'ASC';
    }
    console.log(currentOrder, currentField, field, sortOrder);
    // Construct the URL with the new sorting parameters
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('orderBy', field);
    currentUrl.searchParams.set('order', sortOrder);

    // Redirect to the new URL
    window.location.href = currentUrl.toString();
}

/**
 * This function is only used in conjunction with an external db
 * @param {int} identifier 
 */
function updatePersonInfo(identifier) {
    console.log(identifier);
    $.when(fetchPerson(identifier).done(function(output) {
        if(output.response) {
            console.log(output);
            //var identifier;
            var surname=output.response.membershipAssociationSummaries[0].person.lastName;
            var pref_name=output.response.membershipAssociationSummaries[0].person.firstNames;
            var started=date2timestamp(output.response.membershipAssociationSummaries[0].membershipAssociation.association.from.substr(0,10));
            var primary_key=output.response.membershipAssociationSummaries[0].person.id;
            var data=JSON.stringify(output.response.membershipAssociationSummaries[0]);
            console.log(started);
            $.when(clientCreate(identifier, surname, pref_name, started, primary_key, data)).done(function(result) {
                console.log(result);
                if(result.response.substr(0,5)=="Error") {
                    alert(result.response);
                } else {
                    $('#missing_'+identifier).hide();
                    alert('Added '+pref_name+' '+surname+' to database');
                }

            });
        } else {
            console.log('No matching person');
        }
    }))

}



