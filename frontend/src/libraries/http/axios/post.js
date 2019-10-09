import axios from 'axios'

export default request => {
    let headers = request.headers
    headers['Content-Type'] = 'application/json'
    return new Promise((resolve, reject) => {
        axios.post(request.url, request.body, {
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
