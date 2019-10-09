import axios from 'axios'
import axios_get from './axios/get'
import axios_post from './axios/post'
import axios_put from './axios/put'
import axios_delete from './axios/delete'

const METHOD_GET = 'GET'
const METHOD_POST = 'POST'
const METHOD_PUT = 'PUT'
const METHOD_DELETE = 'DELETE'

class Request {
    constructor(method, url) {
        this.body = null
        this.method = method
        this.url = url
        this.headers = {}
    }
    withBody(body) {
        this.body = body
        return this
    }
    authed(token) {
        this.headers['Authorization'] = 'Bearer ' + token
        return this
    }
    sent() {
        if (this.method === METHOD_GET) {
            return axios_get(this)
        } else if (this.method === METHOD_POST) {
            return axios_post(this)
        } else if (this.method === METHOD_PUT) {
            return axios_put(this)
        } else if (this.method === METHOD_DELETE) {
            return axios_delete(this)
        }
        return Promise(resolve => {
            resolve({
                status: false,
                message: 'Method not allowed!'
            })
        })
    }
}

class Http {
    constructor() {
        this.authorization_token = null
    }
    get(url) {
        let request = new Request(METHOD_GET, url)
        return request
    }
    post(url) {
        let request = new Request(METHOD_POST, url)
        return request
    }
    put(url) {
        let request = new Request(METHOD_PUT, url)
        return request
    }
    delete(url) {
        let request = new Request(METHOD_DELETE, url)
        return request
    }
}

export default new Http()
