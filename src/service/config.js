
const defaultHeaders = {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
};

const ServerAddress = {
    address:'https://zai.mineracao.zabe.com.br',
    basePoint:'/api/v1',
    baseUrl: $`{address}/{basePorint}`
}

const apiServer ={    
    baseUrl: $`{ServerAddress.baseUrl}`,
    auth:{
        base:$`{ServerAddress}/api/auth`,
        token:'/',
        register:'/register',
        update:{
            password:'/updatePass/',
            profile:'updateProff/',
        }
    },
    company:{
        base: $`{ServerAddress.baseUrl}/company`,
    },
    user:{
        base: $`{ServerAddress.baseUrl}/user`,
    },
    data:{
        base: $`{ServerAddress.baseUrl}/company/data`,
    }
}

const AppSettings = {
    headers:defaultHeaders,
    server:apiServer,
}

export default AppSettings