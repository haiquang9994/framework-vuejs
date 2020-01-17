import axios from 'axios'
import appendQuery from 'append-query'

export default request => {
    let headers = request.headers
    let url = request.url
    if (request.body instanceof Object) {
        for (let key in request.body) {
            let val = request.body[key]
            if (typeof val === 'object') {
                val = JSON.stringify(val)
            }
            url = appendQuery(url, key + '=' + val)
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
