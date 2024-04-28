console.log('Loaded OMS javascript file');

function fetchPerson(identifier) {
    return $.ajax({
        url: 'helpers/externaldb/oms.ajax.php',
        method: 'POST',
        data: {method: 'getPerson', member: identifier},
        dataType: 'json'
    })
}