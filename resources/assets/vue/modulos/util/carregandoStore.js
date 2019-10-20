const carregandoStore = {
    _carregando: true,
  set carregando (str) {
    this._carregando = str
  },
  get carregando () {
    return this._carregando
  }
}
export default carregandoStore;