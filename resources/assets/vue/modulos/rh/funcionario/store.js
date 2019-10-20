const store = {
    _nome: '', 
    _nome_completo: '', 
    _email: '', 
    _dt_nascimento: '', 
    _celular_pessoal: '', 
    _celular_corporativo: '', 
    _telefone_comercial: '', 
    _ramal: '', 
    _cargo_id: '', 
    _gestor_id: '',

  set nome (str) {
    this._nome = str
  },
  get nome () {
    return this._nome
  },

  set nome_completo (str) {
    this._nome_completo = str
  },
  get nome_completo () {
    return this._nome_completo
  },

  set email (str) {
    this._email = str
  },
  get email () {
    return this._email
  },

  set dt_nascimento (str) {
    this._dt_nascimento = str
  },
  get dt_nascimento () {
    return this._dt_nascimento
  },

  set celular_pessoal (str) {
    this._celular_pessoal = str
  },
  get celular_pessoal () {
    return this._celular_pessoal
  },

  set celular_corporativo (str) {
    this._celular_corporativo = str
  },
  get celular_corporativo () {
    return this._celular_corporativo
  },

  set telefone_comercial (str) {
    this._telefone_comercial = str
  },
  get telefone_comercial () {
    return this._telefone_comercial
  },

  set ramal (str) {
    this._ramal = str
  },
  get ramal () {
    return this._ramal
  },

  set cargo_id (str) {
    this._cargo_id = str
  },
  get cargo_id () {
    return this._cargo_id
  },

  set gestor_id (str) {
    this._gestor_id = str
  },
  get gestor_id () {
    return this._gestor_id
  }

}
export default store;