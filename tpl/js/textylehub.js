function toggleTextyleAccessType(target) {
    switch(target) {
        case 'domain' :
                xGetElementById('accessTextyleDomain').style.display = 'block';
            break;
        case 'vid' :
                xGetElementById('accessTextyleDomain').style.display = 'none';
            break;
    }
}

