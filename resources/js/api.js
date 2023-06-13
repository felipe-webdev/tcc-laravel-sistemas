const api = {
  login: async function (user, pass){
    // const delay = ms => new Promise(res => setTimeout(res, ms))
    // await delay(1000)
    const reqBody = {
      "user": user,
      "pass": pass
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/login`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'login'
      },
      data: reqBody
    })
    return response
  },

  logout: async function (id_user){
    const reqBody = {
      "id_user": id_user
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/logout`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'logout'
      },
      data: reqBody
    })
    return response
  },

  getSessionUser: async function (){
    const response = await axios({
      method: 'GET',
      url:    `${window.location.origin}/api/getSessionUser`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'getSessionUser'
      }
    })
    return response
  },

  alterPass: async function (id_user, old_pass, new_pass){
    const reqBody = {
      "id_user":  id_user,
      "old_pass": old_pass,
      "new_pass": new_pass
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/alterPass`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'alterPass'
      },
      data: reqBody
    })
    return response
  },

  resetPass: async function (id_user){
    const reqBody = {
      "id_user": id_user
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/resetPass`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'resetPass'
      },
      data: reqBody
    })
    return response
  },

  getSystemTypes: async function (){
    const response = await axios({
      method: 'GET',
      url:    `${window.location.origin}/api/getSystemTypes`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'getSystemTypes'
      }
    })
    return response
  },

  countRecords: async function (){
    const response = await axios({
      method: 'GET',
      url:    `${window.location.origin}/api/countRecords`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'countRecords'
      }
    })
    return response
  },

  insertEmployee: async function (employee){
    const reqBody = {
      "employee": employee
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/insertEmployee`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'insertEmployee'
      },
      data: reqBody
    })
    return response
  },

  insertFamily: async function (id_entity, id_employee, family){
    const reqBody = {
      "id_entity":   id_entity,
      "id_employee": id_employee,
      "family":      family
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/insertFamily`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'insertFamily'
      },
      data: reqBody
    })
    return response
  },

  updateFamily: async function (family){
    const reqBody = {
      "family": family
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/updateFamily`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'updateFamily'
      },
      data: reqBody
    })
    return response
  },

  deleteFamily: async function (id_family, id_person){
    const reqBody = {
      "id_family": id_family,
      "id_person": id_person
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/deleteFamily`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'deleteFamily'
      },
      data: reqBody
    })
    return response
  },

  insertUser: async function (id_employee, user){
    const reqBody = {
      "id_employee": id_employee,
      "user":        user
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/insertUser`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'insertUser'
      },
      data: reqBody
    })
    return response
  },

  isUserAvailable: async function (user){
    const reqBody = {
      "user": user
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/isUserAvailable`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'isUserAvailable'
      },
      data: reqBody
    })
    return response
  },

  listEmployees: async function (name, job_type, active, page, page_limit){
    const reqBody = {
      "name":       !name?          '': name,
      "job_type":   !job_type?      0:  job_type,
      "active":     active == null? -1: active,
      "page":       !page?          0:  page,
      "page_limit": !page_limit?    0:  page_limit
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/listEmployees`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'listEmployees'
      },
      data: reqBody
    })
    return response
  },

  getEmployee: async function (id_employee){
    const reqBody = {
      "id_employee": id_employee
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/getEmployee`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'getEmployee'
      },
      data: reqBody
    })
    return response
  },

  insertEmail: async function (id_person, emails){
    const reqBody = {
      "id_person": id_person,
      "emails":    emails
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/insertEmail`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'insertEmail'
      },
      data: reqBody
    })
    return response
  },

  insertPhone: async function (id_person, phones){
    const reqBody = {
      "id_person": id_person,
      "phones":    phones
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/insertPhone`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'insertPhone'
      },
      data: reqBody
    })
    return response
  },

  deleteEmail: async function (id_email){
    const reqBody = {
      "id_email": id_email,
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/deleteEmail`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'deleteEmail'
      },
      data: reqBody
    })
    return response
  },

  deletePhone: async function (id_phone){
    const reqBody = {
      "id_phone": id_phone,
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/deletePhone`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'deletePhone'
      },
      data: reqBody
    })
    return response
  },

  updateEmail: async function (email){
    const reqBody = {
      "email": email
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/updateEmail`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'updateEmail'
      },
      data: reqBody
    })
    return response
  },

  updatePhone: async function (phone){
    const reqBody = {
      "phone": phone
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/updatePhone`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'updatePhone'
      },
      data: reqBody
    })
    return response
  },

  updateEmployee: async function (part, new_data){
    const reqBody = {
      "part":     part,
      "new_data": new_data
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/updateEmployee`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'updateEmployee'
      },
      data: reqBody
    })
    return response
  },

  listJobs: async function (name, id_job_depart,  page, page_limit){
    const reqBody = {
      "name":          !name?          '': name,
      "id_job_depart": !id_job_depart? 0:  id_job_depart,
      "page":          !page?          0:  page,
      "page_limit":    !page_limit?    0:  page_limit
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/listJobs`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'listJobs'
      },
      data: reqBody
    })
    return response
  },

  listDeparts: async function (name, page, page_limit){
    const reqBody = {
      "name":       !name?          '': name,
      "page":       !page?          0:  page,
      "page_limit": !page_limit?    0:  page_limit
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/listDeparts`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'listDeparts'
      },
      data: reqBody
    })
    return response
  },

  updateDepart: async function (depart){
    const reqBody = {
      "depart": depart
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/updateDepart`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'updateDepart'
      },
      data: reqBody
    })
    return response
  },

  updateJob: async function (job){
    const reqBody = {
      "job": job
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/updateJob`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'updateJob'
      },
      data: reqBody
    })
    return response
  },

  insertDepart: async function (depart){
    const reqBody = {
      "depart": depart
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/insertDepart`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'insertDepart'
      },
      data: reqBody
    })
    return response
  },

  insertJob: async function (job){
    const reqBody = {
      "job": job
    }
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/insertJob`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'application/json; charset=UTF-8',
        'Api':          'insertJob'
      },
      data: reqBody
    })
    return response
  },

  insertImage: async function (formData){
    const response = await axios({
      method: 'POST',
      url:    `${window.location.origin}/api/insertImage`,
      headers: {
        'Accept':       'application/json',
        'Content-Type': 'multipart/form-data',
        'Api':          'insertImage'
      },
      data: formData
    })
    return response
  },

  getImage: async function (id_person){
    const reqBody = {
      'id_person': id_person
    }
    const response = await axios({
      method: 'POST',
      url: `${window.location.origin}/api/getImage`,
      headers: {
        'Content-Type': 'application/json',
        'Api':          'getImage'
      },
      data: reqBody,
      responseType: 'arraybuffer'
    })
    return response
  },
}

export { api }