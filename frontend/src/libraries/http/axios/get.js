import axios from 'axios'

export default request => {
    let headers = request.headers
    return new Promise((resolve, reject) => {
        axios.get(request.url, {
            headers
        }).then(response => {
            if (response.status) {
                resolve(response.data)
            }
        }).catch(e => {
            reject(e)
        })
    })
}
