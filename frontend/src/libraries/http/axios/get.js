import axios from 'axios'
import appendQuery from 'append-query'

export default request => {
    let headers = request.headers
    let url = request.url
    if (request.body instanceof Object) {
        for (let key in request.body) {
            url = appendQuery(url, key + '=' + request.body[key])
        }
    }
    return new Promise((resolve, reject) => {
        axios.get(url, {
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
