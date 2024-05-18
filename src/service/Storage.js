import { crypt, uncrypt } from '@/service/Encrypt';

export function delStorage(key) {
  if (key) {
    localStorage.removeItem(key);
  }
}

export function setStorage(key, value) {
  try {
    if (key && value) {
      const value_encrypted = crypt(value);
      localStorage.setItem(key, value_encrypted);
      return uncrypt(value_encrypted);
    } else {
      console.error(
        "Falha ao tentar salvar valor no storage: Key ou value está faltando"
      );
      return null;
    }
  } catch (error) {
    console.error("Falha ao tentar salvar valor no storage");
    console.error(error);
    return null;
  }
}

export function setJsonStorage(key, value) {
  try {
    if (key && value) {
      const serializedValue = JSON.stringify(value);
      const value_encrypted = crypt(serializedValue);
      localStorage.setItem(key, value_encrypted);
      return uncrypt(value_encrypted);
    } else {
      console.error(
        "Falha ao tentar salvar valor no storage: Key ou value está faltando"
      );
      return null;
    }
  } catch (error) {
    console.error("Falha ao tentar salvar valor no storage");
    console.error(error);
    return null;
  }
}

export function getStorage(key) {
  try {
    if (key) {
      const value_encrypted = localStorage.getItem(key);
      if (value_encrypted) {
        const value_uncrypted = uncrypt(value_encrypted);
        return value_uncrypted;
      } else {
        return null;
      }
    } else {
      console.error(
        "Falha: Key está faltando ao tentar recuperar o valor do storage"
      );
      return null;
    }
  } catch (error) {
    console.error("Falha ao tentar recuperar o valor do storage");
    console.error(error);
    return null;
  }
}

export function getJsonStorage(key) {
  try {
    if (key) {
      const value_encrypted = localStorage.getItem(key);
      if (value_encrypted) {
        const value_uncrypted = uncrypt(value_encrypted);
        const value_serializer = JSON.parse(value_uncrypted);
        return value_serializer;
      } else {
        return null;
      }
    } else {
      console.error(
        "Falha: Key está faltando ao tentar recuperar o valor do storage"
      );
      return null;
    }
  } catch (error) {
    console.error("Falha ao tentar recuperar o valor do storage");
    console.error(error);
    return null;
  }
}
